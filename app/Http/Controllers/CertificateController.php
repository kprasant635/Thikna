<?php

namespace App\Http\Controllers;

use App\Services\LearningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(
        protected LearningService $learningService
    ) {}

    /**
     * View user's official SkopX Certificate.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $learningData = $this->learningService->getUserCourses($user);

        $certificate = $learningData['certificate'];

        if (! $certificate) {
            return redirect()->route('courses.index')->with('warning', 'Please complete all your selected educational courses to unlock your certificate.');
        }

        return view('pages.certificate', [
            'user' => $user,
            'certificate' => $certificate,
            'learningData' => $learningData,
            'isDownload' => false,
        ]);
    }

    /**
     * Downloadable / printable certificate view.
     */
    public function download(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $learningData = $this->learningService->getUserCourses($user);

        $certificate = $learningData['certificate'];

        if (! $certificate) {
            return redirect()->route('courses.index')->with('warning', 'Please complete all your selected educational courses to download your certificate.');
        }

        return view('pages.certificate', [
            'user' => $user,
            'certificate' => $certificate,
            'learningData' => $learningData,
            'isDownload' => true,
        ]);
    }
}
