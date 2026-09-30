<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function index(): View
    {
        $contactInfo = [
            'address' => 'SKOP-X Towers, Plot No. 102/B, Saheed Nagar, Bhubaneswar, Odisha - 751007',
            'phone' => '+91 98765 43210',
            'email' => 'support@skop-x.in',
            'whatsapp' => '+919876543210',
            'hours' => 'Mon - Sat: 9:00 AM - 7:00 PM',
        ];

        $faqs = [
            [
                'question' => 'How quickly will I get a response from support?',
                'answer' => 'Our dedicated support team responds to all inquiries within 2 to 4 business hours. Urgent WhatsApp queries are often handled instantly.',
            ],
            [
                'question' => 'Who should I contact regarding Course Access & Certificates?',
                'answer' => 'You can select "Course & Learning" in the contact form below or reach us directly at support@skop-x.in with your registered email ID and User ID.',
            ],
            [
                'question' => 'Can I visit the SKOP-X office in person?',
                'answer' => 'Yes! Our Bhubaneswar office is open Monday through Saturday from 9:00 AM to 7:00 PM. We recommend scheduling an appointment in advance.',
            ],
            [
                'question' => 'How can I partner or list my business on SKOP-X?',
                'answer' => 'Select "Business & Partnership" in the inquiry form or reach out via WhatsApp. Our business onboarding team will get back to you with all details.',
            ],
        ];

        return view('pages.contact', compact('contactInfo', 'faqs'));
    }

    /**
     * Handle the contact form submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:150',
            'inquiry_type' => 'required|string',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please provide a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'subject.required' => 'Please select or specify a subject.',
            'message.required' => 'Please enter your message (at least 10 characters).',
        ]);

        // Here we log or send mail / store contact inquiry.
        // For demonstration & immediate user feedback, we redirect back with a success message.
        return redirect()->route('contact')->with('success', 'Thank you, '.e($validated['name']).'! Your message has been received. Our support team will reach out to you shortly.');
    }
}
