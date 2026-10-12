<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login page.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login using username & password.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Please enter your admin username or email.',
            'password.required' => 'Please enter your password.',
        ]);

        $loginInput = trim($credentials['username']);

        // Look up by username or email
        $user = User::where(function ($query) use ($loginInput) {
            $query->where('username', $loginInput)
                ->orWhere('email', $loginInput);
        })->first();

        if (! $user || ! $user->isAdmin() || ! Hash::check($credentials['password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid administrator credentials.',
                ], 422);
            }

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => 'Invalid admin username or password, or account lacks administrator privileges.',
                ]);
        }

        // Log the admin in
        $remember = $request->boolean('remember');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Welcome to the Admin Portal!',
                'redirect_url' => route('admin.dashboard'),
            ]);
        }

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Logged in successfully as Administrator.');
    }

    /**
     * Log out of the admin portal.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'You have been logged out of the admin panel.');
    }
}
