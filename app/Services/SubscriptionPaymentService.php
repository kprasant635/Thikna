<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionPaymentService
{
    public const COURSE_MRP = 1000.00;
    public const DISCOUNT = 800.00;
    public const PLATFORM_FEE = 8.00;
    public const GST_RATE = 18.00;
    public const GATEWAY_CHARGE_RATE = 3.00;

    /**
     * Fixed subscription fee in INR.
     */
    public const SUBSCRIPTION_AMOUNT = 200;

    /**
     * Calculate dynamic pricing breakdown.
     */
    public static function getPricingBreakdown(): array
    {
        $courseMrp = (float) (config('subscription.course_mrp') ?? self::COURSE_MRP);
        $discount = (float) (config('subscription.discount') ?? self::DISCOUNT);
        $discountedCourseFee = max(0.00, $courseMrp - $discount);
        $platformFee = (float) (config('subscription.platform_fee') ?? self::PLATFORM_FEE);
        $gstRate = (float) (config('subscription.gst_rate') ?? self::GST_RATE);
        $gatewayRate = (float) (config('subscription.gateway_charge_rate') ?? self::GATEWAY_CHARGE_RATE);

        $gstAmount = round(($discountedCourseFee * $gstRate) / 100, 2);
        $subtotalBeforeGateway = $discountedCourseFee + $platformFee + $gstAmount;
        $gatewayCharge = round(($subtotalBeforeGateway * $gatewayRate) / 100, 2);
        $totalPayable = round($subtotalBeforeGateway + $gatewayCharge, 2);

        return [
            'course_mrp' => $courseMrp,
            'discount' => $discount,
            'discounted_course_fee' => $discountedCourseFee,
            'platform_fee' => $platformFee,
            'gst_rate' => $gstRate,
            'gst_amount' => $gstAmount,
            'gateway_rate' => $gatewayRate,
            'gateway_charge' => $gatewayCharge,
            'total_payable' => $totalPayable,
            'formatted' => [
                'course_mrp' => '₹' . number_format($courseMrp, 2),
                'discount' => '-₹' . number_format($discount, 2),
                'discounted_course_fee' => '₹' . number_format($discountedCourseFee, 2),
                'platform_fee' => '₹' . number_format($platformFee, 2),
                'gst_rate' => number_format($gstRate, 0) . '%',
                'gst_amount' => '₹' . number_format($gstAmount, 2),
                'gateway_rate' => number_format($gatewayRate, 0) . '%',
                'gateway_charge' => '₹' . number_format($gatewayCharge, 2),
                'total_payable' => '₹' . number_format($totalPayable, 2),
            ],
        ];
    }

    /**
     * Create or retrieve a pending payment record for a subscription.
     */
    public function initiatePayment(Subscription $subscription): SubscriptionPayment
    {
        return DB::transaction(function () use ($subscription) {
            $subscription->refresh();

            // Return existing pending payment if available
            $existingPayment = $subscription->payments()
                ->where('status', SubscriptionPayment::STATUS_PENDING)
                ->latest()
                ->first();

            if ($existingPayment) {
                return $existingPayment;
            }

            return SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'order_reference' => 'SUB-' . strtoupper(Str::random(12)),
                'amount' => self::SUBSCRIPTION_AMOUNT,
                'status' => SubscriptionPayment::STATUS_PENDING,
            ]);
        });
    }

    /**
     * Verify payment status and perform user activation inside a database transaction.
     * Idempotent logic ensures duplicate callbacks do not re-process or crash.
     */
    public function verify(SubscriptionPayment $payment, array $payload): SubscriptionPayment
    {
        return DB::transaction(function () use ($payment, $payload) {
            $payment->refresh();

            // Idempotent check: If payment is already marked successful, return as-is
            if ($payment->isSuccess()) {
                return $payment;
            }

            $status = strtolower($payload['status'] ?? 'failed');

            if ($status !== 'success') {
                $failedStatus = in_array($status, ['cancelled', 'canceled']) 
                    ? SubscriptionPayment::STATUS_CANCELLED 
                    : SubscriptionPayment::STATUS_FAILED;

                $payment->update([
                    'status' => $failedStatus,
                    'gateway_payload' => $payload,
                ]);

                $payment->subscription->update([
                    'status' => Subscription::STATUS_FAILED,
                ]);

                return $payment;
            }

            $txnId = $payload['transaction_id'] ?? ('TXN-SUB-' . strtoupper(Str::random(10)));

            // Mark payment success
            $payment->update([
                'status' => SubscriptionPayment::STATUS_SUCCESS,
                'gateway_transaction_id' => $txnId,
                'paid_at' => now(),
                'gateway_payload' => $payload,
            ]);

            // Activate subscription
            $subscription = $payment->subscription;
            $subscription->update([
                'status' => Subscription::STATUS_ACTIVE,
                'activated_at' => now(),
            ]);

            // Activate user
            $user = $subscription->user;
            $user->update([
                'status' => User::STATUS_ACTIVE,
            ]);

            return $payment;
        });
    }
}
