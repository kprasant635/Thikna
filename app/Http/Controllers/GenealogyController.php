<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GenealogyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenealogyController extends Controller
{
    public function __construct(
        protected GenealogyService $genealogyService
    ) {}

    /**
     * Display the member genealogy tree page (User Dashboard).
     */
    public function index(Request $request): View
    {
        $authUser = $request->user();
        $targetUser = $authUser;

        if ($request->filled('root_id')) {
            $requestedUser = User::find($request->query('root_id'));
            if ($requestedUser && $this->genealogyService->isAuthorizedToView($authUser, $requestedUser)) {
                $targetUser = $requestedUser;
            }
        }

        $generations = (int) $request->query('generations', 3);
        $generations = max(1, min(GenealogyService::MAX_PERMITTED_DEPTH, $generations));

        $breadcrumbs = $this->genealogyService->getBreadcrumbs($authUser, $targetUser);
        $rootDetails = $this->genealogyService->getMemberDetails($targetUser, $authUser);

        return view('pages.genealogy', [
            'layout' => 'layouts.dashboard',
            'isAdminView' => false,
            'originalRoot' => $authUser,
            'currentRoot' => $targetUser,
            'rootDetails' => $rootDetails,
            'breadcrumbs' => $breadcrumbs,
            'initialGenerations' => $generations,
        ]);
    }

    /**
     * Display the genealogy tree page in Admin Portal.
     */
    public function adminIndex(Request $request): View
    {
        $authUser = $request->user();

        // Admin defaults to root_id if specified, or first root user, or the admin user
        $targetUser = null;
        if ($request->filled('root_id')) {
            $targetUser = User::find($request->query('root_id'));
        }

        if (! $targetUser) {
            // Find the top-level user with the most referrals or first user
            $targetUser = User::has('referrals')->first() ?? $authUser;
        }

        $originalRoot = $targetUser;
        $generations = (int) $request->query('generations', 3);
        $generations = max(1, min(GenealogyService::MAX_PERMITTED_DEPTH, $generations));

        $breadcrumbs = $this->genealogyService->getBreadcrumbs($originalRoot, $targetUser);
        $rootDetails = $this->genealogyService->getMemberDetails($targetUser, $authUser);

        return view('pages.genealogy', [
            'layout' => 'layouts.admin',
            'isAdminView' => true,
            'originalRoot' => $originalRoot,
            'currentRoot' => $targetUser,
            'rootDetails' => $rootDetails,
            'breadcrumbs' => $breadcrumbs,
            'initialGenerations' => $generations,
        ]);
    }

    /**
     * Fetch the tree JSON data for a root member with generation depth.
     */
    public function treeData(Request $request): JsonResponse
    {
        $authUser = $request->user();
        $rootId = $request->query('root_id');

        $targetUser = $rootId ? User::find($rootId) : $authUser;

        if (! $targetUser) {
            return response()->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        if (! $this->genealogyService->isAuthorizedToView($authUser, $targetUser)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to view this member\'s genealogy.'], 403);
        }

        $rawGen = $request->query('generations', '3');
        $generations = $rawGen === 'all'
            ? GenealogyService::MAX_PERMITTED_DEPTH
            : max(1, min(GenealogyService::MAX_PERMITTED_DEPTH, (int) $rawGen));

        $originalRootId = $request->query('original_root_id');
        $originalRoot = $originalRootId ? (User::find($originalRootId) ?? $authUser) : $authUser;

        $tree = $this->genealogyService->getTreeData($targetUser, $generations);
        $breadcrumbs = $this->genealogyService->getBreadcrumbs($originalRoot, $targetUser);
        $rootDetails = $this->genealogyService->getMemberDetails($targetUser, $authUser, $targetUser->id);

        return response()->json([
            'success' => true,
            'tree' => $tree,
            'breadcrumbs' => $breadcrumbs,
            'root_member' => $rootDetails,
            'generations' => $generations,
        ]);
    }

    /**
     * Lazy-load immediate children for a specific node.
     */
    public function nodeChildren(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if (! $this->genealogyService->isAuthorizedToView($authUser, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to view this node\'s children.'], 403);
        }

        $rootId = $request->query('root_id');
        $rootUser = $rootId ? User::find($rootId) : $authUser;
        if (! $rootUser || ! $this->genealogyService->isAuthorizedToView($authUser, $rootUser)) {
            $rootUser = $authUser;
        }

        $children = $this->genealogyService->getImmediateVisualChildren($user, $rootUser);

        return response()->json([
            'success' => true,
            'parent_id' => $user->id,
            'children' => $children,
            'count' => count($children),
        ]);
    }

    /**
     * Search authorized members by name, username, ID, or referral code.
     */
    public function search(Request $request): JsonResponse
    {
        $authUser = $request->user();
        $query = (string) $request->query('q', '');

        if (strlen(trim($query)) < 1) {
            return response()->json(['success' => true, 'results' => []]);
        }

        $results = $this->genealogyService->searchMembers($authUser, $query);

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Get detailed, authorized information for a single member to show in modal.
     */
    public function memberDetails(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if (! $this->genealogyService->isAuthorizedToView($authUser, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to view member details.'], 403);
        }

        $rootId = $request->query('root_id') ? (int) $request->query('root_id') : null;
        $details = $this->genealogyService->getMemberDetails($user, $authUser, $rootId);

        return response()->json([
            'success' => true,
            'member' => $details,
        ]);
    }
}
