<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReferralController extends Controller
{
    /**
     * Display the total referral list with referrer details, registration date, subscription status, and filters.
     */
    public function index(Request $request): View
    {
        $referrals = User::whereNotNull('referred_by_id')
            ->with(['referrer', 'subscriptions', 'certificates'])
            ->latest()
            ->get();

        $totalReferralsCount = $referrals->count();
        $subscribedCount = $referrals->where('status', User::STATUS_ACTIVE)->count();
        $pendingCount = $referrals->where('status', '!=', User::STATUS_ACTIVE)->count();
        $certifiedCount = $referrals->filter(fn ($u) => $u->isCertified())->count();

        // Calculate conversion rate
        $conversionRate = $totalReferralsCount > 0
            ? round(($subscribedCount / $totalReferralsCount) * 100, 1)
            : 0;

        return view('admin.referrals', compact(
            'referrals',
            'totalReferralsCount',
            'subscribedCount',
            'pendingCount',
            'certifiedCount',
            'conversionRate'
        ));
    }
}
