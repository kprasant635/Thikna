<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class EmployeeProfileController extends Controller
{
    /**
     * Display the digital employee profile linked from the ID card QR code.
     *
     * The page reads directly from the database so level/promotion updates
     * are reflected without reprinting the ID card.
     */
    public function show(User $employee): View
    {
        return view('pages.employee-profile', compact('employee'));
    }
}
