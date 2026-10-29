<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TermsAndConditionsController extends Controller
{
    /**
     * Display the Terms and Conditions page.
     */
    public function index(): View
    {
        return view('pages.terms-conditions');
    }
}
