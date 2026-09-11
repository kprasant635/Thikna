<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $shops = collect([
            [
                'slug' => 'apex-tech-mobile-hub', 'image' => 'images/shop-mobile-hub.jpg', 'icon' => '📱', 'status' => 'Open now', 'verified' => true,
                'name' => 'Apex Tech & Mobile Hub', 'rating' => 4.9, 'reviews' => 342, 'distance' => 'Downtown (1.2 km away)',
                'description' => 'Authorised Apple independent repair provider & Android hardware specialist. Micro-soldering, motherboard liquid damage recovery.',
                'tags' => ['GST registered', 'Free diagnostics', 'OEM parts, 1-yr warranty'],
                'primary_action' => '(361) 555-0192', 'secondary_action' => 'Get instant quote', 'secondary_style' => 'gold',
            ],
            [
                'slug' => 'capitol-city-electronics', 'image' => 'images/shop-electronics-store.jpg', 'icon' => '🔌', 'status' => 'Open now', 'verified' => true,
                'name' => 'Capitol City Electronics & Parts', 'rating' => 4.7, 'reviews' => 189, 'distance' => 'East Guwahati (2.4 km away)',
                'description' => 'Distributor of genuine OEM screens, camera modules, charging ICs, laptop logic boards and bulk repair consumables.',
                'tags' => ['B2B volume tier', 'Overnight shipping', 'Walk-in counter'],
                'primary_action' => 'Show phone number', 'secondary_action' => 'View catalog (45)', 'secondary_style' => 'tint',
            ],
            [
                'slug' => 'swiftscreen-repair-co', 'image' => 'images/shop-screen-repair.jpg', 'icon' => '🖥️', 'status' => 'Open until 9 PM', 'verified' => true,
                'name' => 'SwiftScreen Repair Co.', 'rating' => 4.8, 'reviews' => 520, 'distance' => 'Fancy Bazar (0.8 km away)',
                'description' => 'Fastest glass repair in downtown Guwahati. Dedicated express counter for iPhone, Samsung Galaxy and Pixel.',
                'tags' => ['No appointment needed', 'Price match', '180-day warranty'],
                'primary_action' => 'Book 15-min slot', 'secondary_action' => 'Text store', 'secondary_style' => 'tint',
            ],
            [
                'slug' => 'precision-audio-home-tech', 'image' => 'images/shop-audio-home-tech.jpg', 'icon' => '🎧', 'status' => 'Open now', 'verified' => true,
                'name' => 'Precision Audio & Home Tech', 'rating' => 4.6, 'reviews' => 94, 'distance' => 'Ganeshguri (3.1 km away)',
                'description' => 'Audiophile sound system repair, vinyl turntable restoration and boutique smart-home maintenance.',
                'tags' => ['Custom soldering', 'On-site consultation'],
                'primary_action' => 'Request home visit', 'secondary_action' => 'Send specs', 'secondary_style' => 'tint',
            ],
        ]);

        return view('pages.shops', [
            'city' => 'Guwahati',
            'area' => 'Downtown Guwahati',
            'category' => 'Electronics & Repairs',
            'subcategory' => 'Mobile & Laptop Repair',
            'avgResponseMins' => 4,
            'radiusKm' => 5,
            'activeFilters' => ['Guwahati Downtown (5 km)', 'Verified merchant'],
            'establishmentTypes' => [
                'Authorised service centre' => 38,
                'Independent repair shop' => 94,
                'Wholesale & bulk exporter' => 16,
            ],
            'shops' => $shops,
            'liveActivity' => [
                ['name' => 'Dipankar B.', 'action' => 'requested a screen replacement quote', 'time' => '2 min'],
                ['name' => 'Sarah K.', 'action' => 'booked a tour for a 2BHK flat', 'time' => '6 min'],
                ['name' => 'Rakesh D.', 'action' => 'connected with 2 plumbers', 'time' => '11 min'],
            ],
            'areaCounts' => [
                'Downtown Guwahati' => 420,
                'Ganeshguri' => 310,
                'Fancy Bazar' => 260,
                'Six Mile' => 180,
            ],
        ]);
    }

    public function category(string $category): View
    {
        // Reuses the same listing view, filtered by category slug.
        return $this->index(request()->merge(['category' => $category]));
    }
}
