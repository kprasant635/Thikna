<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessController extends Controller
{
    /**
     * In a real app this would come from a Business model,
     * e.g. Business::where('slug', $business)->firstOrFail().
     */
    protected function findBusiness(string $slug): array
    {
        // Dummy record — every slug currently resolves to the same demo business.
        return [
            'slug' => $slug,
            'name' => 'Apex Tech & Mobile Hub',
            'icon' => '🏬',
            'photo_badge' => 'Official service centre',
            'tagline' => 'Downtown Flagship · Component Distribution · Level-3 Micro-Soldering Certified',
            'category' => 'Electronics & Gadgets',
            'subcategory' => 'Mobile Hub & Hardware Repairs',
            'rank_badge' => 'Rank #1 Verified',
            'phone' => '+913615550192',
            'phone_display' => '(361) 555-0192',
            'whatsapp_url' => 'https://wa.me/913615550192',
            'directions_url' => 'https://maps.google.com/?q=Apex+Tech+Mobile+Hub+Bhubaneswar',
            'address' => '2nd Floor, Fancy Bazar, Downtown Bhubaneswar',
            'badges' => [
                ['label' => '✓ Verified Gold Merchant'],
                ['label' => '12 Years in Business'],
                ['label' => 'ISO 9001:2015 Registered', 'gold' => true],
            ],
            'stats' => [
                ['value' => '30 min', 'label' => 'Avg screen fix'],
                ['value' => '90-day', 'label' => 'Part warranty'],
                ['value' => '100%', 'label' => 'OEM grade'],
            ],
            'photo_count' => 24,
            'review_count' => 342,
            'rating' => 4.9,
            'hours' => [
                ['label' => 'Today', 'value' => '9:00 AM – 8:30 PM', 'today' => true],
                ['label' => 'Tuesday – Saturday', 'value' => '9:00 AM – 8:30 PM'],
                ['label' => 'Sunday', 'value' => 'Closed'],
            ],
            'payment_methods' => [
                'Cash / UPI' => '✓',
                'Credit & debit cards' => '✓',
                'Commercial invoicing (Net-30)' => 'B2B only',
            ],
            'quote_options' => ['iPhone screen replacement', 'Charger / accessory', 'Bulk / wholesale order'],
        ];
    }

    public function show(string $business): View
    {
        $products = [
            ['icon' => '📱', 'name' => 'OEM OLED Display — iPhone 14/15', 'sku' => 'AP-IPH-1415', 'stock_label' => 'In stock (14)', 'price' => 7400, 'cta' => 'Buy / Inquire'],
            ['icon' => '🔌', 'name' => '65W GaN Fast Charger + Type-C Cable', 'sku' => 'PWR-GAN-65W', 'stock_label' => 'In stock (38)', 'price' => 2300, 'cta' => 'Quick order'],
            ['icon' => '🔋', 'name' => 'Anker PowerCore 20,000mAh', 'sku' => 'ANK-PC-20K', 'stock_label' => 'In stock (9)', 'price' => 3700, 'cta' => 'Inquire'],
            ['icon' => '🛡️', 'name' => 'Tempered Glass Protector (10-pack)', 'sku' => 'GL-BULK-10X', 'stock_label' => 'Wholesale tier', 'price' => 1600, 'cta' => 'Wholesale quote'],
        ];

        $reviews = [
            ['name' => 'Marcus R.', 'badge' => 'Verified buyer', 'when' => '3 days ago', 'text' => 'Walked in after shattering my screen right before a meeting. Had the OEM replacement done, water-tested in under 25 minutes, transparently quoted upfront.'],
            ['name' => 'Sarah L.', 'badge' => 'Verified B2B partner', 'when' => '1 week ago', 'text' => 'We source chargers and bulk accessories through Apex Tech. Clean itemised invoices and reliable downtown merchant.'],
        ];

        return view('pages.business-show', [
            'business' => $this->findBusiness($business),
            'products' => $products,
            'reviews' => $reviews,
            'ratingBreakdown' => [5 => 92, 4 => 6, 3 => 1],
        ]);
    }

    public function create(): View
    {
        // Would show the "list your business" onboarding form.
        return view('pages.business-create');
    }

    public function enquiry(string $business): View
    {
        // Would show a contact/enquiry form scoped to this business.
        return view('pages.business-enquiry', [
            'business' => $this->findBusiness($business),
        ]);
    }

    public function quote(Request $request, string $business): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'need' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Would persist a Quote/Lead record here, e.g.:
        // Lead::create([...$request->validated(), 'business_slug' => $business]);

        return back()->with('status', 'Your quote request has been sent to '.$business.'.');
    }
}
