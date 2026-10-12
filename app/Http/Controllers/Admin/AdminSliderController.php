<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSlider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminSliderController extends Controller
{
    /**
     * Display a listing of all landing page sliders.
     */
    public function index(): View
    {
        $sliders = LandingSlider::orderBy('order')->orderBy('id')->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show form for creating a new slider.
     */
    public function create(): View
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ], [
            'title.required' => 'Please provide a title for the slide.',
            'image.required' => 'Please select an image to upload for this slider.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image size cannot exceed 5MB.',
        ]);

        $imagePath = $request->file('image')->store('sliders', 'public');

        LandingSlider::create([
            'title' => $validated['title'],
            'eyebrow' => $validated['eyebrow'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'button_text' => $validated['button_text'] ?: 'Join Now',
            'button_url' => $validated['button_url'] ?: '/register',
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Landing page slider uploaded and created successfully.');
    }

    /**
     * Show form for editing an existing slider.
     */
    public function edit(LandingSlider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update an existing slider.
     */
    public function update(Request $request, LandingSlider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $data = [
            'title' => $validated['title'],
            'eyebrow' => $validated['eyebrow'] ?? null,
            'description' => $validated['description'] ?? null,
            'button_text' => $validated['button_text'] ?: 'Join Now',
            'button_url' => $validated['button_url'] ?: '/register',
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active', false),
        ];

        // If a new image was uploaded
        if ($request->hasFile('image')) {
            // Delete old custom storage image if applicable
            if (! str_starts_with($slider->image, 'images/') && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider details updated successfully.');
    }

    /**
     * Remove an existing slider.
     */
    public function destroy(LandingSlider $slider): RedirectResponse
    {
        if (! str_starts_with($slider->image, 'images/') && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully.');
    }

    /**
     * Quick toggle slider active status.
     */
    public function toggle(LandingSlider $slider): RedirectResponse
    {
        $slider->update([
            'is_active' => ! $slider->is_active,
        ]);

        $statusText = $slider->is_active ? 'enabled' : 'disabled';

        return redirect()->route('admin.sliders.index')
            ->with('success', "Slide has been {$statusText}.");
    }
}
