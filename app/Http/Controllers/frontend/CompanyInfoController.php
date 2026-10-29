<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CompanyInfoController extends Controller
{
    /**
     * Display the company information hub page.
     *
     * This page is the landing destination for the QR code printed on the
     * company footer. It aggregates company details, objectives, tourism,
     * educational, fitness, cooking, economic awareness, and media content.
     */
    public function index(): View
    {
        $courses = Course::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->take(6)
            ->get(['id', 'title', 'description', 'thumbnail', 'img_url']);

        return view('pages.company-info', compact('courses'));
    }
}
