@extends('layouts.admin')

@section('title', 'Upload New Slider — SKOP-X Admin')
@section('breadcrumb', 'Landing Sliders / Upload New')

@section('content')

    <div style="max-width: 780px; margin: 0 auto;">
        
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a;">
                    Upload Landing Page Slider
                </h1>
                <p style="color: #64748b; font-size: 13.5px; margin-top: 3px;">
                    Add a new hero banner slide to feature on the homepage carousel.
                </p>
            </div>

            <a href="{{ route('admin.sliders.index') }}" style="color: #64748b; text-decoration: none; font-size: 13.5px; font-weight: 600;">
                ← Back to Sliders
            </a>
        </div>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
                <strong>Please correct the following errors:</strong>
                <ul style="margin-left: 20px; margin-top: 6px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container-card">
            <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Image Upload Section with Live Preview --}}
                <div class="form-field">
                    <label class="field-label">
                        Slide Image <span style="color: #ef4444;">*</span>
                    </label>
                    <div class="image-upload-dropzone" onclick="document.getElementById('slideImageInput').click();">
                        <input type="file" id="slideImageInput" name="image" accept="image/*" style="display: none;" onchange="previewSlideImage(this)" required>
                        <div id="dropzonePlaceholder">
                            <div style="font-size: 32px; margin-bottom: 8px;">📷</div>
                            <div style="font-weight: 600; color: #1e293b; font-size: 14px;">Click to select slide image</div>
                            <div style="color: #64748b; font-size: 12px; margin-top: 4px;">PNG, JPG, WEBP up to 5MB (Recommended: 800x600 or 1200x800)</div>
                        </div>
                        <img id="imagePreview" src="" alt="Preview" style="display: none; max-height: 220px; width: auto; border-radius: 8px; margin: 0 auto; object-fit: contain;">
                    </div>
                    @error('image') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                {{-- Eyebrow / Tag --}}
                <div class="form-field">
                    <label for="eyebrow" class="field-label">Eyebrow Tag (Optional Subtitle)</label>
                    <input type="text" id="eyebrow" name="eyebrow" class="form-input"
                        value="{{ old('eyebrow', 'SKOP-X LEARNING') }}" placeholder="e.g. SKOP-X LEARNING or FEATURED COURSE">
                    <div class="field-hint">Small uppercase badge shown above the main title.</div>
                </div>

                {{-- Slide Title --}}
                <div class="form-field">
                    <label for="title" class="field-label">Slide Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="title" name="title" class="form-input"
                        value="{{ old('title') }}" placeholder="e.g. Master High-Income Digital Skills" required>
                    @error('title') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                {{-- Slide Description --}}
                <div class="form-field">
                    <label for="description" class="field-label">Description Text</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3"
                        placeholder="Brief summary of the course or opportunity featured in this slide...">{{ old('description') }}</textarea>
                </div>

                {{-- Button CTA & URL --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-field">
                        <label for="button_text" class="field-label">Call to Action Button Text</label>
                        <input type="text" id="button_text" name="button_text" class="form-input"
                            value="{{ old('button_text', 'Join Now') }}" placeholder="e.g. Join Now or View Course">
                    </div>
                    <div class="form-field">
                        <label for="button_url" class="field-label">Button Target URL</label>
                        <input type="text" id="button_url" name="button_url" class="form-input"
                            value="{{ old('button_url', '/register') }}" placeholder="e.g. /register or /course-catalog">
                    </div>
                </div>

                {{-- Order & Active Status --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: center; margin-top: 10px;">
                    <div class="form-field" style="margin-bottom: 0;">
                        <label for="order" class="field-label">Display Order</label>
                        <input type="number" id="order" name="order" class="form-input"
                            value="{{ old('order', 1) }}" min="0">
                        <div class="field-hint">Lower numbers appear first in the slider rotation.</div>
                    </div>

                    <div style="padding-top: 18px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">Enable slide immediately (Make Live)</span>
                        </label>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.sliders.index') }}" class="btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn-submit-slide">
                        <span>Upload &amp; Save Slide</span>
                        <span>→</span>
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function previewSlideImage(input) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('dropzonePlaceholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        .form-container-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 7px;
        }

        .form-input, .form-textarea {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 13.5px;
            color: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-input:focus, .form-textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .field-hint {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 5px;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        .image-upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }

        .image-upload-dropzone:hover {
            border-color: #2563eb;
            background: #f1f5f9;
        }

        .btn-submit-slide {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transition: all 0.15s;
        }

        .btn-submit-slide:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-cancel:hover { background: #e2e8f0; }
    </style>
@endpush
