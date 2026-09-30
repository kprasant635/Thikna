<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = [
            ['icon' => '📊', 'title' => 'Digital Skills', 'color' => '#2563eb'],
            ['icon' => '📈', 'title' => 'Business Growth', 'color' => '#dc2626'],
            ['icon' => '💰', 'title' => 'Financial Freedom', 'color' => '#059669'],
            ['icon' => '🏆', 'title' => 'Recognition', 'color' => '#d97706'],
            ['icon' => '👥', 'title' => 'Community', 'color' => '#7c3aed'],
            ['icon' => '🎧', 'title' => 'Support 24x7', 'color' => '#0891b2'],
        ];

        $courses = Course::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'description']);

        $recentJoinings = User::query()
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'address', 'referral_code', 'created_at', 'profile_photo'])
            ->map(fn (User $user): array => [
                'name' => $user->name,
                'id' => $user->referral_code ?? 'SK'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                'city' => $user->address ?? 'Address not provided',
                'date' => $user->created_at?->format('d M'),
                'avatar' => $user->profile_photo ?? $user->initials(),
            ])
            ->all();

        $featuredVideos = [
            ['title' => 'Why SKOP-X?', 'desc' => 'Know the opportunity', 'duration' => '2:45', 'thumbnail' => 'promo-male-learner.jpg'],
            ['title' => 'How to Start?', 'desc' => 'Step by step guide', 'duration' => '5:18', 'thumbnail' => 'hero-female-learner.jpg'],
            ['title' => 'Earning Plan Explained', 'desc' => 'Your income potential', 'duration' => '6:20', 'thumbnail' => 'hero-female-learner1.jpg'],
            ['title' => 'Success Stories', 'desc' => 'Real people, real results', 'duration' => '4:10', 'thumbnail' => 'growth-plant.jpg'],
        ];

        $topAchievers = [
            ['name' => 'Anil Kumar', 'level' => 'Gold Achiever', 'amount' => '1,25,000', 'avatar' => 'AK', 'color' => '#f59e0b'],
            ['name' => 'Sunita Mishra', 'level' => 'Silver Achiever', 'amount' => '95,000', 'avatar' => 'SM', 'color' => '#9ca3af'],
            ['name' => 'Rohit Das', 'level' => 'Silver Achiever', 'amount' => '82,000', 'avatar' => 'RD', 'color' => '#9ca3af'],
            ['name' => 'Meena Rath', 'level' => 'Bronze Achiever', 'amount' => '68,000', 'avatar' => 'MR', 'color' => '#b45309'],
            ['name' => 'Prakash Sahu', 'level' => 'Bronze Achiever', 'amount' => '60,000', 'avatar' => 'PS', 'color' => '#b45309'],
        ];

        $birthdays = [
            ['name' => 'Ankita Nayak', 'message' => 'Many happy returns!', 'date' => '15 Sep', 'avatar' => 'AN'],
            ['name' => 'Sambit Kumar', 'message' => 'Wishing you a great year!', 'date' => '15 Sep', 'avatar' => 'SK'],
            ['name' => 'Rupa Das', 'message' => 'Stay happy always!', 'date' => '16 Sep', 'avatar' => 'RD'],
            ['name' => 'Vikash Mohanty', 'message' => 'Have a wonderful day!', 'date' => '16 Sep', 'avatar' => 'VM'],
        ];

        $anniversaries = [
            ['names' => 'Rakesh & Priyanka', 'message' => 'Happy Anniversary!', 'date' => '15 Sep', 'avatars' => ['RP', 'PR']],
            ['names' => 'Suresh & Mamata', 'message' => 'Stay blessed together!', 'date' => '17 Sep', 'avatars' => ['SM', 'MA']],
            ['names' => 'Amit & Neha', 'message' => 'Wishing you endless love!', 'date' => '17 Sep', 'avatars' => ['AN', 'NE']],
            ['names' => 'Manoj & Pooja', 'message' => 'Happy Anniversary!', 'date' => '18 Sep', 'avatars' => ['MP', 'PO']],
        ];

        return view('pages.home', [
            'categories' => $categories,
            'courses' => $courses,
            'recentJoinings' => $recentJoinings,
            'featuredVideos' => $featuredVideos,
            'topAchievers' => $topAchievers,
            'birthdays' => $birthdays,
            'anniversaries' => $anniversaries,
        ]);
    }
}
