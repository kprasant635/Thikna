<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    protected function allRentals(): array
    {
        return [
            [
                'slug' => 'spacious-2bhk-domain-north-Bhubaneswar',
                'image' => 'images/rental-apartment-2bhk.jpg',
                'icon' => '🏠', 'tag' => 'Verified property', 'tag_style' => '',
                'title' => 'Spacious 2 BHK Modern Apartment with Balcony',
                'location' => 'The Domain, North Bhubaneswar (0.4 km to Tech Park)',
                'price' => 14000, 'deposit_label' => 'deposit: 1 month rent',
                'facts' => ['2 Beds', '2 Baths', '1,180 sq ft', 'Ready to move'],
                'tags' => ['Semi-furnished', 'Covered parking', 'Pet friendly'],
                'secondary_action' => 'Schedule free visit',
            ],
            [
                'slug' => 'luxury-3bhk-penthouse-south-ganeshguri',
                'image' => 'images/rental-penthouse-3bhk.jpg',
                'icon' => '🏙️', 'tag' => 'Skyline tier', 'tag_style' => 'gold',
                'title' => 'Luxury 3 BHK Penthouse with Skyline City Views',
                'location' => 'South Ganeshguri (SoCo)',
                'price' => 34500, 'deposit_label' => 'deposit: ₹30,000',
                'facts' => ['3 Beds', '3 Baths', '1,850 sq ft', '1st / Penthouse'],
                'tags' => ['Fully furnished', 'Gated community', '24/7 concierge'],
                'secondary_action' => 'WhatsApp',
            ],
            [
                'slug' => 'cosy-1bhk-studio-west-campus',
                'image' => 'images/rental-studio-1bhk.jpg',
                'icon' => '🛏️', 'tag' => 'Student friendly', 'tag_style' => '',
                'title' => 'Cosy 1 BHK Studio Apartment near Gauhati University',
                'location' => 'West Campus (3 min walk to Quad)',
                'price' => 9500, 'deposit_label' => 'utilities fixed at ₹850',
                'facts' => ['1 BHK studio', '620 sq ft', 'Fully furnished'],
                'tags' => ['High-speed fibre', 'Bike storage', 'Keyless smart lock'],
                'secondary_action' => 'Schedule visit',
            ],
        ];
    }

    public function index(Request $request): View
    {
        return view('pages.rentals', [
            'city' => 'Bhubaneswar',
            'area' => $request->get('area', 'North Bhubaneswar'),
            'updatedAgo' => '8 min',
            'avgRent' => 15400,
            'avgRentTrend' => '−1.2% vs last year',
            'activeFilters' => ['Zero brokerage'],
            'otherFilters' => ['Furnished', 'Pet friendly', 'Immediate move-in', 'Gated society'],
            'propertyTypes' => ['Apartment / Independent', 'Studio', 'Independent House'],
            'bhkOptions' => ['2 BHK & 3 BHK', '1 BHK', 'Any'],
            'budgetOptions' => ['₹8,000 – ₹25,000', 'Under ₹8,000', '₹25,000+'],
            'rentals' => $this->allRentals(),
        ]);
    }

    public function show(string $rental): View
    {
        $property = collect($this->allRentals())->firstWhere('slug', $rental)
            ?? $this->allRentals()[0];

        // Would normally be its own detail view; reusing the business-show
        // layout pattern is a reasonable starting point for a property page.
        return view('pages.rental-show', [
            'property' => $property,
        ]);
    }
}
