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
    public function profile(): View
    {
        return view('pages.profile');
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
        ], [
            'name.required' => 'Full name is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'address.required' => 'Complete address is required.',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $validated['name'],
            'email' => ! empty($validated['email']) ? strtolower(trim($validated['email'])) : null,
            'address' => $validated['address'],
        ]);

        return redirect()->route('dashboard.profile')->with('success', 'Profile updated successfully.');
    }
}
