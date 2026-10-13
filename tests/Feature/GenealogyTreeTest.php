<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenealogyTreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_genealogy_page(): void
    {
        $response = $this->get(route('dashboard.genealogy'));
        $response->assertRedirect(route('register'));
    }

    public function test_authenticated_user_can_view_genealogy_tree_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Root Leader',
            'referral_code' => 'SKOPX123456',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.genealogy'));

        $response->assertStatus(200);
        $response->assertSee('Genealogy Tree');
        $response->assertSee('Root Leader');
        $response->assertSee('SKOPX123456');
    }

    public function test_admin_can_view_genealogy_tree_in_admin_portal(): void
    {
        $admin = User::factory()->create([
            'name' => 'System Admin',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.genealogy'));

        $response->assertStatus(200);
        $response->assertSee('Genealogy Tree');
        $response->assertSee('Admin Portal');
    }

    public function test_root_with_no_referrals_renders_empty_descendant_state(): void
    {
        $root = User::factory()->create(['name' => 'Solo Member', 'referral_code' => 'SOLO01']);

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(0, $json['tree']['direct_referrals_count']);
        $this->assertEquals(0, $json['tree']['visual_children_count']);
        $this->assertFalse($json['tree']['has_children']);
        $this->assertEmpty($json['tree']['children']);
    }

    public function test_root_with_fewer_than_10_referrals_places_all_directly(): void
    {
        $root = User::factory()->create(['name' => 'Root 5']);
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => "Child {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(5, $json['tree']['direct_referrals_count']);
        $this->assertEquals(5, $json['tree']['visual_children_count']);
        $this->assertCount(5, $json['tree']['children']);
        foreach ($json['tree']['children'] as $child) {
            $this->assertEquals($root->id, $child['visual_parent_id']);
            $this->assertEquals($root->id, $child['actual_referrer_id']);
            $this->assertFalse($child['is_spillover']);
        }
    }

    public function test_root_with_exactly_10_referrals_fills_root_capacity(): void
    {
        $root = User::factory()->create(['name' => 'Root 10']);
        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'name' => "Child {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(10, $json['tree']['direct_referrals_count']);
        $this->assertEquals(10, $json['tree']['visual_children_count']);
        $this->assertCount(10, $json['tree']['children']);
    }

    public function test_root_with_11_direct_referrals_places_11th_under_first_child_via_bfs(): void
    {
        $root = User::factory()->create(['name' => 'Root 11']);
        $children = [];
        for ($i = 1; $i <= 11; $i++) {
            $children[] = User::factory()->create([
                'name' => "Member {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        // Database still has 11 actual direct referrals for Root
        $this->assertEquals(11, $root->referrals()->count());

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();

        // Root has 11 actual referrals, but STRICTLY 10 visual direct children
        $this->assertEquals(11, $json['tree']['direct_referrals_count']);
        $this->assertEquals(10, $json['tree']['visual_children_count']);
        $this->assertCount(10, $json['tree']['children']);

        // The 11th member must be placed under Child 1 (Member 1)
        $child1 = $json['tree']['children'][0];
        $this->assertEquals('Member 1', $child1['name']);
        $this->assertEquals(1, $child1['visual_children_count']);
        $this->assertCount(1, $child1['children']);

        $placed11th = $child1['children'][0];
        $this->assertEquals('Member 11', $placed11th['name']);
        $this->assertEquals($child1['id'], $placed11th['visual_parent_id']); // Placed visually under Child 1
        $this->assertEquals($root->id, $placed11th['actual_referrer_id']); // Actual referrer is still Root!
        $this->assertTrue($placed11th['is_spillover']);

        // Verify actual database relationship was NOT modified
        $this->assertEquals($root->id, User::find($children[10]->id)->referred_by_id);
    }

    public function test_root_with_12_or_more_referrals_places_subsequent_under_first_child_in_bfs_order(): void
    {
        $root = User::factory()->create(['name' => 'Root 12']);
        for ($i = 1; $i <= 12; $i++) {
            User::factory()->create([
                'name' => "Member {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertCount(10, $json['tree']['children']);
        $child1 = $json['tree']['children'][0];
        $this->assertEquals('Member 1', $child1['name']);

        // Members 11 and 12 are placed under Member 1
        $this->assertEquals(2, $child1['visual_children_count']);
        $this->assertCount(2, $child1['children']);
        $this->assertEquals('Member 11', $child1['children'][0]['name']);
        $this->assertEquals('Member 12', $child1['children'][1]['name']);
    }

    public function test_full_descendant_with_10_children_spills_over_to_next_bfs_sibling(): void
    {
        $root = User::factory()->create(['name' => 'Root Matrix']);
        $level1 = [];
        for ($i = 1; $i <= 10; $i++) {
            $level1[] = User::factory()->create([
                'name' => "Child {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        // Child 1 gets 10 direct referrals of its own (filling Child 1)
        for ($j = 1; $j <= 10; $j++) {
            User::factory()->create([
                'name' => "SubChild 1-{$j}",
                'referred_by_id' => $level1[0]->id,
                'created_at' => now()->addHours(1)->addMinutes($j),
            ]);
        }

        // Now root refers an 11th member
        $root11 = User::factory()->create([
            'name' => 'Root Overflow',
            'referred_by_id' => $root->id,
            'created_at' => now()->addHours(2),
        ]);

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 3,
        ]));

        $response->assertStatus(200);
        $json = $response->json();

        // Child 1 is full (10 visual children)
        $child1 = $json['tree']['children'][0];
        $this->assertEquals(10, $child1['visual_children_count']);

        // Root Overflow cannot go under Child 1 because Child 1 is full.
        // It must spill over to Child 2 (the next available parent at Level 1)!
        $child2 = $json['tree']['children'][1];
        $this->assertEquals('Child 2', $child2['name']);
        $this->assertEquals(1, $child2['visual_children_count']);
        $this->assertCount(1, $child2['children']);
        $this->assertEquals('Root Overflow', $child2['children'][0]['name']);
        $this->assertEquals($child2['id'], $child2['children'][0]['visual_parent_id']);
        $this->assertEquals($root->id, $child2['children'][0]['actual_referrer_id']);
        $this->assertTrue($child2['children'][0]['is_spillover']);
    }

    public function test_generation_filtering_limits_tree_depth(): void
    {
        $root = User::factory()->create(['name' => 'Gen 1']);
        $child = User::factory()->create(['name' => 'Gen 2', 'referred_by_id' => $root->id]);
        User::factory()->create(['name' => 'Gen 3', 'referred_by_id' => $child->id]);

        // Request only 1 generation (root only, children not loaded)
        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $root->id,
            'generations' => 1,
        ]));

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals(1, $json['generations']);
        $this->assertFalse($json['tree']['children_loaded']);
        $this->assertEmpty($json['tree']['children']);
        $this->assertEquals(1, $json['tree']['direct_referrals_count']);
        $this->assertEquals(1, $json['tree']['visual_children_count']);
    }

    public function test_lazy_loading_node_children_endpoint(): void
    {
        $parent = User::factory()->create(['name' => 'Parent User']);
        User::factory()->create(['name' => 'Sub Child A', 'referred_by_id' => $parent->id]);
        User::factory()->create(['name' => 'Sub Child B', 'referred_by_id' => $parent->id]);

        $response = $this->actingAs($parent)->getJson(route('dashboard.genealogy.children', [
            'user' => $parent->id,
            'root_id' => $parent->id,
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'parent_id' => $parent->id,
            'count' => 2,
        ]);
        $json = $response->json();
        $this->assertEquals('Sub Child A', $json['children'][0]['name']);
        $this->assertEquals('Sub Child B', $json['children'][1]['name']);
    }

    public function test_non_admin_cannot_view_arbitrary_unauthorized_user_tree(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B (Unrelated)']);

        // User A tries to view User B's tree
        $response = $this->actingAs($userA)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $userB->id,
        ]));

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => "Unauthorized to view this member's genealogy.",
        ]);
    }

    public function test_non_admin_can_view_descendant_user_tree(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B', 'referred_by_id' => $userA->id]);
        $userC = User::factory()->create(['name' => 'User C', 'referred_by_id' => $userB->id]);

        // User A can view User C's tree because C is in A's downline
        $response = $this->actingAs($userA)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $userC->id,
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'tree' => [
                'id' => $userC->id,
                'name' => 'User C',
            ],
        ]);
    }

    public function test_admin_can_view_any_user_tree(): void
    {
        $admin = User::factory()->create(['name' => 'Admin User', 'is_admin' => true]);
        $unrelated = User::factory()->create(['name' => 'Any Target Member']);

        $response = $this->actingAs($admin)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $unrelated->id,
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'tree' => [
                'id' => $unrelated->id,
                'name' => 'Any Target Member',
            ],
        ]);
    }

    public function test_search_endpoint_filters_by_name_username_id_and_code(): void
    {
        $root = User::factory()->create(['name' => 'Root Leader']);
        $child1 = User::factory()->create([
            'name' => 'Alice Wonder',
            'username' => 'alicew',
            'referral_code' => 'SKOPX9911',
            'referred_by_id' => $root->id,
        ]);
        $child2 = User::factory()->create([
            'name' => 'Bob Builder',
            'username' => 'bobb',
            'referral_code' => 'SKOPX8822',
            'referred_by_id' => $root->id,
        ]);

        // Search by name
        $resName = $this->actingAs($root)->getJson(route('dashboard.genealogy.search', ['q' => 'Alice']));
        $resName->assertStatus(200);
        $this->assertCount(1, $resName->json('results'));
        $this->assertEquals('Alice Wonder', $resName->json('results.0.name'));

        // Search by username
        $resUser = $this->actingAs($root)->getJson(route('dashboard.genealogy.search', ['q' => 'bobb']));
        $resUser->assertStatus(200);
        $this->assertCount(1, $resUser->json('results'));
        $this->assertEquals('Bob Builder', $resUser->json('results.0.name'));

        // Search by referral code
        $resCode = $this->actingAs($root)->getJson(route('dashboard.genealogy.search', ['q' => 'SKOPX9911']));
        $resCode->assertStatus(200);
        $this->assertCount(1, $resCode->json('results'));
        $this->assertEquals('Alice Wonder', $resCode->json('results.0.name'));

        // Search by ID
        $resId = $this->actingAs($root)->getJson(route('dashboard.genealogy.search', ['q' => (string) $child2->id]));
        $resId->assertStatus(200);
        $this->assertCount(1, $resId->json('results'));
        $this->assertEquals('Bob Builder', $resId->json('results.0.name'));
    }

    public function test_member_details_modal_distinguishes_actual_referrer_and_visual_parent(): void
    {
        $root = User::factory()->create(['name' => 'Root Master']);
        $level1 = [];
        for ($i = 1; $i <= 10; $i++) {
            $level1[] = User::factory()->create([
                'name' => "Child {$i}",
                'referred_by_id' => $root->id,
                'created_at' => now()->addMinutes($i),
            ]);
        }

        // 11th member referred by Root Master (spills under Child 1)
        $spilloverUser = User::factory()->create([
            'name' => 'Spillover Member',
            'username' => 'spilluser',
            'referred_by_id' => $root->id,
            'referral_code' => 'SKOPX1111',
            'created_at' => now()->addMinutes(11),
        ]);

        $response = $this->actingAs($root)->getJson(route('dashboard.genealogy.member', [
            'user' => $spilloverUser->id,
            'root_id' => $root->id,
        ]));

        $response->assertStatus(200);
        $json = $response->json('member');

        $this->assertEquals('Spillover Member', $json['name']);
        $this->assertEquals('Root Master', $json['referrer']['name']); // Actual sponsor
        $this->assertEquals($level1[0]->id, $json['visual_parent']['id']); // Visual placement parent
        $this->assertEquals('Child 1', $json['visual_parent']['name']);
        $this->assertTrue($json['is_spillover']);

        // Sensitive data not exposed
        $this->assertArrayNotHasKey('password', $json);
        $this->assertArrayNotHasKey('bank_account_number', $json);
    }

    public function test_circular_referral_does_not_cause_infinite_loop(): void
    {
        $userA = User::factory()->create(['name' => 'Member Alpha']);
        $userB = User::factory()->create(['name' => 'Member Beta', 'referred_by_id' => $userA->id]);

        // Artificially simulate loop (Alpha -> Beta -> Alpha)
        $userA->referred_by_id = $userB->id;
        $userA->save();

        $response = $this->actingAs($userA)->getJson(route('dashboard.genealogy.tree', [
            'root_id' => $userA->id,
            'generations' => 5,
        ]));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));
    }
}
