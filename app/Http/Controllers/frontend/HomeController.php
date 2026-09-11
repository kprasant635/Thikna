<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sectors = [
            [
                'icon' => '🏪', 'count' => '2,400+ shops', 'title' => 'Local Shops & Services',
                'description' => 'Grocery, electronics, tailoring and boutique retail across Guwahati.',
                'cta' => 'Browse shops', 'url' => route('shops.index'),
            ],
            [
                'icon' => '🏠', 'count' => '640+ listings', 'title' => 'Home Rentals & Realty',
                'description' => 'Verified flats, studios and independent houses, no agent markup.',
                'cta' => 'See properties', 'url' => route('rentals.index'),
            ],
            [
                'icon' => '📦', 'count' => '3,100+ catalogs', 'title' => 'Products & B2B',
                'description' => 'Direct manufacturers, wholesale stock and bulk supplies.',
                'cta' => 'Source products', 'url' => route('business.show', ['business' => 'apex-tech-mobile-hub']),
            ],
            [
                'icon' => '🔧', 'count' => '420+ experts', 'title' => 'Emergency & Repairs',
                'description' => 'On-call plumbers, electricians and roadside mechanics.',
                'cta' => 'Get urgent help', 'url' => route('shops.index'),
            ],
        ];

        $featuredMerchants = [
            [
                'slug' => 'apex-tech-mobile-hub', 'icon' => '📱', 'status' => 'Open now',
                'image' => 'images/shop-mobile-hub.jpg',
                'name' => 'Apex Tech & Mobile Hub', 'rating' => 4.9, 'reviews' => 342,
                'location' => 'Downtown Guwahati, 1.2 km away',
                'description' => 'Authorised repair provider and mobile accessories retailer — screen replacements, chargers, and bulk accessories.',
                'tags' => ['Free diagnostics', 'OEM parts', '1-yr warranty'],
            ],
            [
                'slug' => 'borah-plumbing-sanitation', 'icon' => '🚰', 'status' => 'Open now',
                'image' => 'images/merchant-plumbing.jpg',
                'name' => 'Borah Plumbing & Sanitation', 'rating' => 4.8, 'reviews' => 219,
                'location' => 'Zoo Road, Guwahati',
                'description' => 'Leak repair, tankless water heater installs and residential mainline clearance. 24 hours active.',
                'tags' => ['Hydro-jetting', 'No night surcharge'],
            ],
            [
                'slug' => 'assam-hardware-bulk-supplies', 'icon' => '🏗️', 'status' => 'Open now',
                'image' => 'images/merchant-hardware.jpg',
                'name' => 'Assam Hardware & Bulk Supplies', 'rating' => 4.9, 'reviews' => 198,
                'location' => 'Fancy Bazar, Guwahati',
                'description' => 'Wholesale distributor for electrical components, conduit, and bulk fasteners. Same-day pickup available.',
                'tags' => ['B2B accounts', 'Commercial credit'],
            ],
        ];

        $featuredRentals = [
            ['image' => 'images/home-rental-beltola.jpg',    'icon' => '🏠', 'tag' => '2 BHK · Zero brokerage', 'title' => 'Modern Apartment — Beltola',      'location' => 'Beltola, Guwahati · 950 sq ft',       'price' => 14000],
            ['image' => 'images/home-rental-chandmari.jpg',  'icon' => '🏢', 'tag' => '1 BHK · Furnished',      'title' => 'Cosy Flat — Chandmari',          'location' => 'Chandmari, Guwahati · 560 sq ft',     'price' => 9500],
            ['image' => 'images/home-rental-hengrabari.jpg', 'icon' => '🏡', 'tag' => '3 BHK · Independent',   'title' => 'House with Parking — Hengrabari', 'location' => 'Hengrabari, Guwahati · 1400 sq ft',   'price' => 22000],
        ];

        $featuredProducts = [
            ['image' => 'images/product-iphone-screen.jpg', 'icon' => '📱', 'category' => 'Mobile parts', 'name' => 'OEM OLED Screen — iPhone 14/15',  'seller' => 'Apex Tech & Mobile Hub',       'seller_slug' => 'apex-tech-mobile-hub', 'price' => 7400],
            ['image' => 'images/product-bicycle.jpg',       'icon' => '🚲', 'category' => 'Sports',       'name' => 'Hero Sprint Bicycle',              'seller' => 'Nath Cycle Store · Six Mile',  'seller_slug' => 'nath-cycle-store',     'price' => 5600],
            ['image' => 'images/product-study-table.jpg',   'icon' => '🛋️', 'category' => 'Furniture',    'name' => 'Wooden Study Table',               'seller' => 'Rahman Furniture · Uzan Bazar','seller_slug' => 'rahman-furniture',     'price' => 3200],
        ];

        $deals = [
            ['off' => '25% OFF', 'merchant' => 'Assam Hardware Co.', 'description' => "Bulk fasteners and power tool rentals this week.", 'code' => 'HARDWARE25'],
            ['off' => '₹500 OFF', 'merchant' => 'Hill Country Rentals', 'description' => "Discount on first month's rent for 12+ month leasing.", 'code' => 'MOVEIN500'],
            ['off' => 'FREE CHECK', 'merchant' => 'Apex Tech & Mobile Hub', 'description' => 'Complimentary battery health and charging port check.', 'code' => 'DEVICECHECK'],
        ];

        return view('pages.home', [
            'city' => 'Guwahati',
            'reviewCount' => 18000,
            'sectors' => $sectors,
            'featuredMerchants' => $featuredMerchants,
            'featuredRentals' => $featuredRentals,
            'featuredProducts' => $featuredProducts,
            'deals' => $deals,
        ]);
    }
}
