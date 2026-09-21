<?php

namespace App\Http\Controllers;

use App\Services\LearningService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BenefitsController extends Controller
{
    public function __construct(
        protected LearningService $learningService
    ) {}

    /**
     * Display exclusive SkopX member benefits unlocked after certification.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $learningData = $this->learningService->getUserCourses($user);

        $benefits = [
            [
                'id' => 1,
                'title' => '🌟 Verified SkopX Member Badge',
                'description' => 'Display a golden verified badge on all your yellow-pages business listings and profile.',
                'status' => 'Unlocked',
                'category' => 'Trust & Visibility',
            ],
            [
                'id' => 2,
                'title' => '🚀 Priority Directory Search Placement',
                'description' => 'Get ranked above standard listings in shop and service searches across your city.',
                'status' => 'Unlocked',
                'category' => 'Promotion',
            ],
            [
                'id' => 3,
                'title' => '💼 B2B & Wholesale Networking Access',
                'description' => 'Direct contact access to premium suppliers, vendors, and contractors.',
                'status' => 'Unlocked',
                'category' => 'Networking',
            ],
            [
                'id' => 4,
                'title' => '🎓 Lifetime Educational Content Vault',
                'description' => 'Unlimited lifetime access to all short-video masterclasses and upcoming modules.',
                'status' => 'Unlocked',
                'category' => 'Learning',
            ],
            [
                'id' => 5,
                'title' => '📞 Dedicated Account Manager Support',
                'description' => 'Direct VIP helpline for listing management, customer support, and leads.',
                'status' => 'Unlocked',
                'category' => 'VIP Support',
            ],
        ];

        return view('pages.benefits', [
            'user' => $user,
            'learningData' => $learningData,
            'benefits' => $benefits,
        ]);
    }
}
