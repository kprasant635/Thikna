<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    public function test_pending_user_accessing_dashboard_is_redirected_to_subscription(): void
    {
        $user = User::create([
            'name' => 'Pending User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('subscription.show'));
        $response->assertSessionHas('warning');
    }

    public function test_subscription_page_renders_with_16_products(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)->get(route('subscription.show'));

        $response->assertStatus(200);
        $response->assertSee('Welcome to Thikana!');
        $response->assertSee('Product (Education / Training)');
        $response->assertSee('Story (Billion/Story)');
        $response->assertSee('Subscription Fee: ₹200');
    }

    public function test_selecting_less_than_3_products_fails_validation(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        // Submit 2 products only
        $response = $this->actingAs($user)
            ->postJson(route('subscription.checkout'), [
                'products' => [1, 2],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products']);
    }

    public function test_selecting_invalid_product_ids_fails_validation(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('subscription.checkout'), [
                'products' => [1, 2, 9999],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['products.2']);
    }

    public function test_selecting_at_least_3_valid_products_creates_pending_subscription_and_payment(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('subscription.checkout'), [
                'products' => [1, 2, 3],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'payment' => [
                'amount' => 200,
                'currency' => 'INR',
            ],
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_PENDING,
        ]);

        $subscription = Subscription::where('user_id', $user->id)->first();
        $this->assertCount(3, $subscription->products);

        $this->assertDatabaseHas('subscription_payments', [
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'amount' => 200,
            'status' => SubscriptionPayment::STATUS_PENDING,
        ]);
    }

    public function test_successful_payment_verification_activates_user_and_subscription(): void
    {
        $user = User::create([
            'name' => 'Payment User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_PENDING,
        ]);
        $subscription->products()->sync([1, 2, 3, 4]);

        $payment = SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'order_reference' => 'SUB-TESTORDER123',
            'amount' => 200,
            'status' => SubscriptionPayment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('subscription.payment.verify'), [
                'order_reference' => 'SUB-TESTORDER123',
                'status' => 'success',
                'transaction_id' => 'TXN-RAZORPAY-999',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect_url' => route('dashboard'),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => Subscription::STATUS_ACTIVE,
        ]);

        $this->assertDatabaseHas('subscription_payments', [
            'id' => $payment->id,
            'status' => SubscriptionPayment::STATUS_SUCCESS,
            'gateway_transaction_id' => 'TXN-RAZORPAY-999',
        ]);
    }

    public function test_payment_verification_is_idempotent(): void
    {
        $user = User::create([
            'name' => 'Idempotent User',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Guwahati',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);

        $payment = SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'order_reference' => 'SUB-IDEMPOTENT1',
            'gateway_transaction_id' => 'TXN-EXISTING-001',
            'amount' => 200,
            'status' => SubscriptionPayment::STATUS_SUCCESS,
            'paid_at' => now(),
        ]);

        // Repeat verification callback
        $response = $this->actingAs($user)
            ->postJson(route('subscription.payment.verify'), [
                'order_reference' => 'SUB-IDEMPOTENT1',
                'status' => 'success',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function test_failed_or_cancelled_payment_keeps_user_pending(): void
    {
        $user = User::create([
            'name' => 'Failed Payment User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_PENDING,
        ]);

        $payment = SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'order_reference' => 'SUB-FAIL12345',
            'amount' => 200,
            'status' => SubscriptionPayment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('subscription.payment.verify'), [
                'order_reference' => 'SUB-FAIL12345',
                'status' => 'cancelled',
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('subscription_payments', [
            'id' => $payment->id,
            'status' => SubscriptionPayment::STATUS_CANCELLED,
        ]);
    }

    public function test_active_user_accessing_subscription_page_is_redirected_to_dashboard(): void
    {
        $user = User::create([
            'name' => 'Active User',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)->get(route('subscription.show'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_active_user_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Active User',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Guwahati',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Welcome to Thikana, Active User');
    }

    public function test_returning_pending_user_login_redirects_to_subscription(): void
    {
        $user = User::create([
            'name' => 'Pending Login User',
            'phone' => '9876543210',
            'status' => User::STATUS_PENDING,
            'address' => 'Guwahati',
        ]);

        $response = $this->post(route('login.submit'), [
            'phone' => '9876543210',
        ]);

        $response->assertRedirect(route('subscription.show'));
        $this->assertTrue(auth()->check());
    }

    public function test_returning_active_user_login_redirects_to_dashboard(): void
    {
        $user = User::create([
            'name' => 'Active Login User',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Guwahati',
        ]);

        $response = $this->post(route('login.submit'), [
            'phone' => '9876543210',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(auth()->check());
    }
}
