<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin overview dashboard with system metrics.
     */
    public function index(): View
    {
        $totalReferralsCount = User::whereNotNull('referred_by_id')->count();
        $totalActiveUsersCount = User::where('status', User::STATUS_ACTIVE)->count();
        $totalInactiveUsersCount = User::where('status', '!=', User::STATUS_ACTIVE)->count();
        $totalCourseCount = Course::count();
        $totalSubscribeCount = Subscription::where('status', Subscription::STATUS_ACTIVE)->count();

        // Additional valuable metrics
        $totalUsersCount = User::count();
        $totalRevenue = (float) Subscription::where('status', Subscription::STATUS_ACTIVE)->sum('amount');
        $certifiedUsersCount = User::where('learning_status', User::LEARNING_CERTIFIED)->count();

        // Recent activity
        $recentUsers = User::with('referrer')->latest()->take(8)->get();
        $recentSubscriptions = Subscription::with(['user', 'products'])->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalReferralsCount',
            'totalActiveUsersCount',
            'totalInactiveUsersCount',
            'totalCourseCount',
            'totalSubscribeCount',
            'totalUsersCount',
            'totalRevenue',
            'certifiedUsersCount',
            'recentUsers',
            'recentSubscriptions'
        ));
    }
}
