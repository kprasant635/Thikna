<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
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
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
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

    public function updatePaymentDetails(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => ['required', Rule::in(['none', 'bank', 'upi'])],
            'bank_account_holder' => ['nullable', 'required_if:payment_method,bank', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:payment_method,bank', 'string', 'max:255'],
            'bank_account_number' => [
                'nullable',
                'string',
                'regex:/^[0-9]{8,20}$/',
                Rule::requiredIf(fn (): bool => $request->input('payment_method') === 'bank'
                    && blank($request->user()->bank_account_number)),
            ],
            'bank_ifsc' => ['nullable', 'required_if:payment_method,bank', 'string', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'],
            'upi_id' => ['nullable', 'required_if:payment_method,upi', 'string', 'max:255', 'regex:/^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+$/'],
        ], [
            'bank_account_number.required' => 'Enter your bank account number.',
            'bank_account_number.regex' => 'Enter a valid bank account number using 8 to 20 digits.',
            'bank_ifsc.required_if' => 'Enter your bank IFSC code.',
            'bank_ifsc.regex' => 'Enter a valid 11-character IFSC code.',
            'upi_id.required_if' => 'Enter your UPI ID.',
            'upi_id.regex' => 'Enter a valid UPI ID, such as name@bank.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dashboard.profile')
                ->withErrors($validator)
                ->withInput($request->except('bank_account_number'));
        }

        $validated = $validator->validated();
        $user = $request->user();
        $paymentMethod = $validated['payment_method'];

        $user->payment_method = $paymentMethod === 'none' ? null : $paymentMethod;
        $user->bank_account_holder = $paymentMethod === 'bank' ? trim($validated['bank_account_holder']) : null;
        $user->bank_name = $paymentMethod === 'bank' ? trim($validated['bank_name']) : null;
        $user->bank_ifsc = $paymentMethod === 'bank' ? strtoupper(trim($validated['bank_ifsc'])) : null;
        $user->upi_id = $paymentMethod === 'upi' ? trim($validated['upi_id']) : null;

        if ($paymentMethod === 'bank' && filled($validated['bank_account_number'] ?? null)) {
            $user->bank_account_number = trim($validated['bank_account_number']);
        } elseif ($paymentMethod !== 'bank') {
            $user->bank_account_number = null;
        }

        $user->save();

        return redirect()->route('dashboard.profile')->with('success', 'Payment details updated successfully.');
    }

    /**
     * Download Virtual ID Card as PDF.
     */
    public function downloadIdCard(Request $request)
    {
        $user = $request->user();

        $pdf = Pdf::loadView('pdf.virtual_id_card', [
            'user' => $user,
        ]);

        return $pdf->setPaper('a5', 'portrait')
            ->download('SkopX_Virtual_ID_Card_'.($user->referral_code ?? $user->id).'.pdf');
    }

    public function downloadPdf()
    {
        $file = public_path('pdf/business.pdf');

        return response()->download($file, 'Business.pdf');
    }
}
