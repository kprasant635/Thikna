<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use SplQueue;

class GenealogyService
{
    /**
     * Maximum visual children permitted directly beneath any visual parent.
     */
    public const MAX_VISUAL_CHILDREN = 10;

    /**
     * Maximum depth to prevent runaway recursion or circular references.
     */
    public const MAX_PERMITTED_DEPTH = 10;

    /**
     * Build the hierarchical tree array starting from the specified root user,
     * applying the recursive 10-position visual placement rule.
     *
     * @return array<string, mixed>
     */
    public function getTreeData(User $rootUser, int $generations = 3): array
    {
        $generations = max(1, min(self::MAX_PERMITTED_DEPTH, $generations));
        $visualNodes = $this->buildVisualPlacementTree($rootUser);

        return $this->formatVisualNode($rootUser->id, $visualNodes, 1, $generations);
    }

    /**
     * Build the full visual placement tree map for a given root user.
     *
     * Enforces the 10-position visual placement rule:
     * - Every visual parent has a maximum of 10 direct children.
     * - Actual direct referrals can be unlimited in the database.
     * - When a visual parent has 10 children, level-by-level left-to-right BFS is used
     *   to find the first parent with an open visual vacancy (< 10 children).
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildVisualPlacementTree(User $rootUser): array
    {
        $downlineIds = $this->getAllDownlineIds($rootUser->id);

        $members = User::whereIn('id', $downlineIds)
            ->select(['id', 'name', 'username', 'referral_code', 'profile_photo', 'status', 'referred_by_id', 'created_at'])
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $visualNodes = [];
        $visualNodes[$rootUser->id] = [
            'user' => $rootUser,
            'visual_parent_id' => null,
            'visual_children' => [],
            'visual_depth' => 1,
            'is_spillover' => false,
        ];

        foreach ($members as $member) {
            $preferredParentId = $member->referred_by_id;
            if (! $preferredParentId || ! isset($visualNodes[$preferredParentId])) {
                $preferredParentId = $rootUser->id;
            }

            $placementParentId = $this->findBfsPlacementParent($preferredParentId, $visualNodes, $rootUser->id);

            $visualNodes[$placementParentId]['visual_children'][] = $member->id;

            $isSpillover = ($member->referred_by_id !== $placementParentId);
            $visualNodes[$member->id] = [
                'user' => $member,
                'visual_parent_id' => $placementParentId,
                'visual_children' => [],
                'visual_depth' => $visualNodes[$placementParentId]['visual_depth'] + 1,
                'is_spillover' => $isSpillover,
            ];
        }

        return $visualNodes;
    }

    /**
     * Find the first eligible visual parent with fewer than 10 children using level-by-level BFS.
     *
     * @param  array<int, array<string, mixed>>  $visualNodes
     */
    public function findBfsPlacementParent(int $startParentId, array &$visualNodes, int $rootId): int
    {
        // 1. If startParent has room, place directly under it
        if (isset($visualNodes[$startParentId]) && count($visualNodes[$startParentId]['visual_children']) < self::MAX_VISUAL_CHILDREN) {
            return $startParentId;
        }

        // 2. BFS search level by level, left to right
        $queue = new SplQueue;
        $visited = [$startParentId => true];

        if (isset($visualNodes[$startParentId])) {
            foreach ($visualNodes[$startParentId]['visual_children'] as $childId) {
                $queue->enqueue($childId);
            }
        }

        while (! $queue->isEmpty()) {
            $candidateId = $queue->dequeue();

            if (isset($visited[$candidateId])) {
                continue;
            }
            $visited[$candidateId] = true;

            if (isset($visualNodes[$candidateId])) {
                if (count($visualNodes[$candidateId]['visual_children']) < self::MAX_VISUAL_CHILDREN) {
                    return $candidateId;
                }

                // Enqueue candidate's visual children for the next level
                foreach ($visualNodes[$candidateId]['visual_children'] as $childId) {
                    if (! isset($visited[$childId])) {
                        $queue->enqueue($childId);
                    }
                }
            }
        }

        // 3. Fallback: If startParent was not the root and its subtree was full, search the whole root tree
        if ($startParentId !== $rootId && isset($visualNodes[$rootId])) {
            return $this->findBfsPlacementParent($rootId, $visualNodes, $rootId);
        }

        return $startParentId;
    }

    /**
     * Format a visual placement node into a structured array up to max depth.
     *
     * @param  array<int, array<string, mixed>>  $visualNodes
     * @return array<string, mixed>
     */
    public function formatVisualNode(int $nodeId, array &$visualNodes, int $currentDepth, int $maxDepth): array
    {
        $nodeData = $visualNodes[$nodeId];
        /** @var User $user */
        $user = $nodeData['user'];
        $visualChildrenIds = $nodeData['visual_children'];
        $visualChildrenCount = count($visualChildrenIds);
        $hasVisualChildren = $visualChildrenCount > 0;

        $actualReferrer = $user->referrer;
        $visualParentUser = ($nodeData['visual_parent_id'] && isset($visualNodes[$nodeData['visual_parent_id']]))
            ? $visualNodes[$nodeData['visual_parent_id']]['user']
            : null;

        $formatted = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'referral_code' => $user->referral_code,
            'profile_photo_url' => $user->profilePhotoUrl(),
            'initials' => $user->initials(),
            'status' => $user->status ?? User::STATUS_PENDING,
            'direct_referrals_count' => $user->referrals()->count(),
            'visual_children_count' => $visualChildrenCount,
            'has_children' => $hasVisualChildren,
            'depth' => $currentDepth,
            'visual_parent_id' => $nodeData['visual_parent_id'],
            'visual_parent_name' => $visualParentUser?->name,
            'actual_referrer_id' => $user->referred_by_id,
            'actual_referrer_name' => $actualReferrer?->name,
            'is_spillover' => (bool) $nodeData['is_spillover'],
            'children_loaded' => false,
            'children' => [],
        ];

        if ($hasVisualChildren && $currentDepth < $maxDepth) {
            $children = [];
            foreach ($visualChildrenIds as $childId) {
                if (isset($visualNodes[$childId])) {
                    $children[] = $this->formatVisualNode($childId, $visualNodes, $currentDepth + 1, $maxDepth);
                }
            }
            $formatted['children'] = $children;
            $formatted['children_loaded'] = true;
        }

        return $formatted;
    }

    /**
     * Get the immediate visual children of a specific node for lazy loading.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getImmediateVisualChildren(User $parentUser, User $rootUser): array
    {
        $visualNodes = $this->buildVisualPlacementTree($rootUser);

        if (! isset($visualNodes[$parentUser->id])) {
            return [];
        }

        $children = [];
        $parentDepth = $visualNodes[$parentUser->id]['visual_depth'];
        foreach ($visualNodes[$parentUser->id]['visual_children'] as $childId) {
            if (isset($visualNodes[$childId])) {
                $children[] = $this->formatVisualNode($childId, $visualNodes, $parentDepth + 1, $parentDepth + 1);
            }
        }

        return $children;
    }

    /**
     * Check whether an authenticated user is authorized to view a target user's genealogy.
     * Admins can view any user; regular members can only view themselves or their downline.
     */
    public function isAuthorizedToView(User $authUser, User $targetUser): bool
    {
        if ($authUser->isAdmin()) {
            return true;
        }

        if ($authUser->id === $targetUser->id) {
            return true;
        }

        return $this->isDescendantOf($targetUser, $authUser);
    }

    /**
     * Verify if a target user is a descendant of the given ancestor.
     */
    public function isDescendantOf(User $descendant, User $ancestor): bool
    {
        $currentId = $descendant->referred_by_id;
        $visited = [$descendant->id => true];
        $safetyCounter = 0;

        while ($currentId && $safetyCounter < 50) {
            if ($currentId === $ancestor->id) {
                return true;
            }

            if (isset($visited[$currentId])) {
                // Cycle detected, abort safely
                break;
            }

            $visited[$currentId] = true;
            $currentId = User::where('id', $currentId)->value('referred_by_id');
            $safetyCounter++;
        }

        return false;
    }

    /**
     * Search for members matching name, username, ID, or referral code.
     * Admins search all users; regular users only search themselves and their downline.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function searchMembers(User $authUser, string $query, int $limit = 10): Collection
    {
        $query = trim($query);
        if ($query === '') {
            return collect();
        }

        $baseQuery = User::query()
            ->select(['id', 'name', 'username', 'referral_code', 'profile_photo', 'status', 'referred_by_id']);

        if (! $authUser->isAdmin()) {
            $downlineIds = $this->getAllDownlineIds($authUser->id);
            $allowedIds = array_merge([$authUser->id], $downlineIds);
            $baseQuery->whereIn('id', $allowedIds);
        }

        $baseQuery->where(function ($sub) use ($query) {
            $sub->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('referral_code', 'like', "%{$query}%");

            if (is_numeric($query)) {
                $sub->orWhere('id', (int) $query);
            }
        });

        return $baseQuery->limit($limit)->get()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'referral_code' => $user->referral_code,
                'profile_photo_url' => $user->profilePhotoUrl(),
                'initials' => $user->initials(),
                'status' => $user->status ?? User::STATUS_PENDING,
                'direct_referrals_count' => $user->referrals()->count(),
            ];
        });
    }

    /**
     * Fetch all downline user IDs under a root user using a safe BFS traversal.
     *
     * @return array<int>
     */
    public function getAllDownlineIds(int $rootId, int $maxDepth = 25): array
    {
        $allDownlineIds = [];
        $currentLevelIds = [$rootId];
        $visited = [$rootId => true];
        $depth = 0;

        while (! empty($currentLevelIds) && $depth < $maxDepth) {
            $nextLevelIds = User::whereIn('referred_by_id', $currentLevelIds)
                ->whereNotIn('id', array_keys($visited))
                ->pluck('id')
                ->all();

            if (empty($nextLevelIds)) {
                break;
            }

            foreach ($nextLevelIds as $id) {
                $visited[$id] = true;
                $allDownlineIds[] = $id;
            }

            $currentLevelIds = $nextLevelIds;
            $depth++;
        }

        return $allDownlineIds;
    }

    /**
     * Build breadcrumbs from the root user down to the current focused user.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getBreadcrumbs(User $originalRoot, User $currentRoot): array
    {
        if ($originalRoot->id === $currentRoot->id) {
            return [
                [
                    'id' => $originalRoot->id,
                    'name' => $originalRoot->name,
                    'referral_code' => $originalRoot->referral_code,
                    'is_current' => true,
                ],
            ];
        }

        // Trace path up from currentRoot to originalRoot
        $path = [];
        $cursor = $currentRoot;
        $visited = [];

        while ($cursor && ! isset($visited[$cursor->id])) {
            $visited[$cursor->id] = true;
            array_unshift($path, [
                'id' => $cursor->id,
                'name' => $cursor->name,
                'referral_code' => $cursor->referral_code,
                'is_current' => $cursor->id === $currentRoot->id,
            ]);

            if ($cursor->id === $originalRoot->id) {
                break;
            }

            $parentId = $cursor->referred_by_id;
            if (! $parentId) {
                break;
            }

            $cursor = User::find($parentId);
        }

        // If path doesn't start with originalRoot, prepend originalRoot
        if (empty($path) || $path[0]['id'] !== $originalRoot->id) {
            array_unshift($path, [
                'id' => $originalRoot->id,
                'name' => $originalRoot->name,
                'referral_code' => $originalRoot->referral_code,
                'is_current' => false,
            ]);
        }

        return $path;
    }

    /**
     * Get formatted member details for modal display.
     *
     * @return array<string, mixed>
     */
    public function getMemberDetails(User $member, User $authUser, ?int $rootId = null): array
    {
        $actualReferrer = $member->referrer;
        $visualParent = null;
        $isSpillover = false;
        $visualChildrenCount = 0;

        if ($rootId) {
            $rootUser = User::find($rootId);
            if ($rootUser) {
                $visualNodes = $this->buildVisualPlacementTree($rootUser);
                if (isset($visualNodes[$member->id])) {
                    $visualChildrenCount = count($visualNodes[$member->id]['visual_children']);
                    $parentId = $visualNodes[$member->id]['visual_parent_id'];
                    if ($parentId && isset($visualNodes[$parentId])) {
                        $parentUser = $visualNodes[$parentId]['user'];
                        $visualParent = [
                            'id' => $parentUser->id,
                            'name' => $parentUser->name,
                            'referral_code' => $parentUser->referral_code,
                        ];
                        $isSpillover = ($member->referred_by_id !== $parentId);
                    }
                }
            }
        }

        return [
            'id' => $member->id,
            'name' => $member->name,
            'username' => $member->username ?? null,
            'referral_code' => $member->referral_code,
            'profile_photo_url' => $member->profilePhotoUrl(),
            'initials' => $member->initials(),
            'status' => $member->status ?? User::STATUS_PENDING,
            'direct_referrals_count' => $member->referrals()->count(),
            'visual_children_count' => $visualChildrenCount,
            'joining_date' => $member->created_at ? $member->created_at->format('d M Y, h:i A') : 'N/A',
            'referrer' => $actualReferrer ? [
                'id' => $actualReferrer->id,
                'name' => $actualReferrer->name,
                'referral_code' => $actualReferrer->referral_code,
            ] : null,
            'visual_parent' => $visualParent,
            'is_spillover' => $isSpillover,
            'can_set_as_root' => $this->isAuthorizedToView($authUser, $member),
        ];
    }
}
