<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionPaymentService
{
    /**
     * Fixed subscription fee in INR.
     */
    public const SUBSCRIPTION_AMOUNT = 200;

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
