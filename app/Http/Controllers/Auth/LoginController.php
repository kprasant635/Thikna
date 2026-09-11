<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user->isActive() 
                ? redirect()->route('dashboard') 
                : redirect()->route('subscription.show');
        }

        return view('pages.login');
    }

    /**
     * Process login submission via Mobile / OTP or Phone lookup.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
        ], [
            'phone.required' => 'Please enter your mobile number.',
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
        ]);

        $phone = $this->otpService->normalizePhone($request->phone);
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found with this mobile number. Please register first.',
                ], 404);
            }
            return back()->withInput()->withErrors(['phone' => 'No account found with this mobile number. Please register first.']);
        }

        // Log the user in
        Auth::login($user);

        // Redirect based on user status
        $redirectUrl = $user->isActive() ? route('dashboard') : route('subscription.show');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $user->isActive() ? 'Welcome back!' : 'Please complete your subscription to activate your account.',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with(
            $user->isActive() ? 'success' : 'warning',
            $user->isActive() ? 'Welcome back to Thikana!' : 'Please complete your subscription to activate your account.'
        );
    }
}
