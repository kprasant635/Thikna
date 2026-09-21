<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index(): View
    {
        return view('pages.dashboard');
    }

    /**
     * Show the profile edit page.
     */
    public function profile(Request $request): View
    {
        $user = $request->user();
        $referrals = $user->referrals()->withCount('referrals')->latest()->get();

        return view('pages.profile', compact('referrals'));
    }

    /**
     * Show the user's referrals list page.
     */
    public function referrals(Request $request): View
    {
        $user = $request->user();
        $referrals = $user->referrals()->withCount('referrals')->latest()->get();

        return view('pages.referrals', compact('user', 'referrals'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'address' => ['required', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'name.required' => 'Full name is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'address.required' => 'Complete address is required.',
            'profile_photo.image' => 'The profile photo must be a valid image file.',
            'profile_photo.max' => 'Profile photo size must not exceed 5MB.',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->name = $validated['name'];
        $user->email = ! empty($validated['email']) ? strtolower(trim($validated['email'])) : null;
        $user->address = $validated['address'];
        $user->save();

        return redirect()->route('dashboard.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Download Virtual ID Card as PDF.
     */
    public function downloadIdCard(Request $request)
    {
        $user = $request->user();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.virtual_id_card', [
            'user' => $user,
        ]);

        return $pdf->setPaper('a5', 'portrait')
            ->download('SkopX_Virtual_ID_Card_' . ($user->referral_code ?? $user->id) . '.pdf');
    }
}

