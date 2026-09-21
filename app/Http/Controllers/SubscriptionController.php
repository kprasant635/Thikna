<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionPaymentService $paymentService
    ) {}

    /**
     * Show the subscription product selection and payment page.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If user is already active, redirect to dashboard
        if ($user && $user->isActive()) {
            return redirect()->route('dashboard');
        }

        $products = Product::where('is_active', true)->get();
        
        $pendingSubscription = $user->subscriptions()
            ->with(['products', 'latestPayment'])
            ->where('status', Subscription::STATUS_PENDING)
            ->latest()
            ->first();

        $selectedProductIds = $pendingSubscription ? $pendingSubscription->products->pluck('id')->toArray() : [];
        $latestPayment = $pendingSubscription?->latestPayment;

        $pricing = SubscriptionPaymentService::getPricingBreakdown();

        return view('pages.subscription', [
            'user' => $user,
            'products' => $products,
            'selectedProductIds' => $selectedProductIds,
            'pendingSubscription' => $pendingSubscription,
            'latestPayment' => $latestPayment,
            'pricing' => $pricing,
            'subscriptionAmount' => $pricing['total_payable'],
        ]);
    }

    /**
     * Store chosen products and initiate fixed ₹200 payment.
     */
    public function processSelection(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($user->isActive()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is already active.',
                    'redirect_url' => route('dashboard'),
                ]);
            }
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'integer', 'exists:products,id'],
        ], [
            'products.required' => 'Please select at least 1 course to continue.',
            'products.array' => 'Invalid course selection.',
            'products.min' => 'You must select at least 1 course to activate your account.',
            'products.*.exists' => 'Selected course is invalid or no longer available.',
        ]);

        try {
            $result = DB::transaction(function () use ($user, $validated) {
                // Find or create pending subscription
                $subscription = Subscription::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'status' => Subscription::STATUS_PENDING,
                    ],
                    [
                        'amount' => SubscriptionPaymentService::SUBSCRIPTION_AMOUNT,
                    ]
                );

                // Attach chosen products
                $subscription->products()->sync($validated['products']);

                // Create or retrieve pending payment record
                $payment = $this->paymentService->initiatePayment($subscription);

                return [
                    'subscription' => $subscription,
                    'payment' => $payment,
                ];
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Products saved. Proceed to payment.',
                    'payment' => [
                        'order_reference' => $result['payment']->order_reference,
                        'amount' => SubscriptionPaymentService::SUBSCRIPTION_AMOUNT,
                        'currency' => 'INR',
                    ],
                    'redirect_url' => route('subscription.show', ['order' => $result['payment']->order_reference]),
                ]);
            }

            return redirect()->route('subscription.show', ['order' => $result['payment']->order_reference])
                ->with('success', 'Products saved successfully. Please complete the ₹200 payment.');
        } catch (\Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to save products. Please try again.',
                ], 500);
            }

            return back()->withErrors(['error' => 'An error occurred while saving your product selection.']);
        }
    }

    /**
     * Verify payment status with backend logic and activate user upon success.
     */
    public function verifyPayment(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'order_reference' => ['required', 'string'],
            'status' => ['required', 'string', 'in:success,failed,cancelled'],
            'transaction_id' => ['nullable', 'string'],
        ]);

        $payment = SubscriptionPayment::where('order_reference', $request->order_reference)->first();

        if (! $payment) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment reference not found.',
                ], 444);
            }
            return redirect()->route('subscription.show')->withErrors(['payment' => 'Invalid payment reference.']);
        }

        // Enforce security: verify payment belongs to current user
        if ($payment->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized payment access.');
        }

        $verifiedPayment = $this->paymentService->verify($payment, [
            'status' => $request->status,
            'transaction_id' => $request->transaction_id ?? $request->input('gateway_transaction_id'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($verifiedPayment->isSuccess()) {
            $request->session()->flash('activation_success', true);
            $request->session()->flash('success', '🎉 Congratulations! Your SkopX account is now active.');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully! Your account is now active.',
                    'redirect_url' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard');
        }

        $errorMessage = 'Payment was not completed. Your account is still inactive. Please complete the ₹200 subscription to activate your account.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'redirect_url' => route('subscription.show'),
            ], 422);
        }

        return redirect()->route('subscription.show')->withErrors(['payment' => $errorMessage]);
    }

    /**
     * Download payment receipt in PDF format.
     */
    public function downloadReceipt(Request $request, ?SubscriptionPayment $payment = null)
    {
        $user = $request->user();

        if (! $payment || ! $payment->exists) {
            $payment = SubscriptionPayment::where('user_id', $user->id)
                ->where('status', SubscriptionPayment::STATUS_SUCCESS)
                ->latest()
                ->first();

            if (! $payment) {
                return redirect()->route('subscription.show')->withErrors(['payment' => 'No completed payment receipt found.']);
            }
        }

        if ($payment->user_id !== $user->id) {
            abort(403, 'Unauthorized access to payment receipt.');
        }

        $subscription = $payment->subscription ? $payment->subscription->load('products') : null;
        $pricing = SubscriptionPaymentService::getPricingBreakdown();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.subscription_receipt', [
            'payment' => $payment,
            'subscription' => $subscription,
            'user' => $user,
            'pricing' => $pricing,
        ]);

        return $pdf->download('SkopX_Subscription_Receipt_' . $payment->order_reference . '.pdf');
    }
}
