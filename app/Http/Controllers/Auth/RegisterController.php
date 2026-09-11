<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('pages.register');
    }

    /**
     * Send OTP to the given phone number via AJAX.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
        ], [
            'phone.required' => 'Please enter your mobile number.',
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
        ]);

        $phone = $this->otpService->normalizePhone($request->phone);

        // Check if phone number is already registered
        if (User::where('phone', $phone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This mobile number is already registered. Please log in or use another number.',
                'errors' => [
                    'phone' => ['This mobile number is already registered.'],
                ],
            ], 422);
        }

        try {
            $result = $this->otpService->sendOtp($phone, $request->ip(), $request->userAgent());

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first() ?? 'Unable to send OTP.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP due to a server error. Please try again later.',
            ], 500);
        }
    }

    /**
     * Verify the OTP via AJAX.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Mobile number must be 10 digits.',
            'otp.required' => 'Please enter the 6-digit OTP.',
            'otp.regex' => 'OTP must be exactly 6 digits.',
        ]);

        $phone = $this->otpService->normalizePhone($request->phone);

        try {
            $result = $this->otpService->verifyOtp($phone, $request->otp);

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first() ?? 'Invalid OTP.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed due to a server error. Please try again.',
            ], 500);
        }
    }

    /**
     * Process registration submission.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:users,phone'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'referral_code' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
        ], [
            'name.required' => 'Please enter your full name.',
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
            'phone.unique' => 'This mobile number is already registered.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'address.required' => 'Complete address is required.',
        ]);

        $phone = $this->otpService->normalizePhone($validated['phone']);

        // Confirm server-side OTP verification
        if (! $this->otpService->isPhoneVerified($phone)) {
            return back()
                ->withInput()
                ->withErrors([
                    'phone' => 'Please verify your mobile number via OTP before completing registration.',
                ]);
        }

        // Validate referral code if provided
        $referrer = null;
        if (! empty($validated['referral_code'])) {
            $refCode = strtoupper(trim($validated['referral_code']));
            $referrer = User::where('referral_code', $refCode)->first();

            if (! $referrer) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'referral_code' => 'The referral code entered is invalid.',
                    ]);
            }
        }

        try {
            $user = DB::transaction(function () use ($validated, $phone, $referrer) {
                $createdUser = User::create([
                    'name' => $validated['name'],
                    'phone' => $phone,
                    'phone_verified_at' => now(),
                    'email' => ! empty($validated['email']) ? strtolower(trim($validated['email'])) : null,
                    'address' => $validated['address'],
                    'status' => User::STATUS_PENDING,
                    'referral_code' => User::generateUniqueReferralCode(),
                    'referred_by_id' => $referrer?->id,
                ]);

                $this->otpService->markAsConsumed($phone);

                return $createdUser;
            });

            // Log the user in
            Auth::login($user);

            return redirect()->route('subscription.show')->with('success', 'Your account has been created successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'An unexpected error occurred during registration. Please try again.',
                ]);
        }
    }
}
