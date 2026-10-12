@extends('layouts.admin')

@section('title', 'Edit Slider — SKOP-X Admin')
@section('breadcrumb', 'Landing Sliders / Edit')

@section('content')

    <div style="max-width: 780px; margin: 0 auto;">
        
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 700; color: #0f172a;">
                    Edit Landing Page Slider
                </h1>
                <p style="color: #64748b; font-size: 13.5px; margin-top: 3px;">
                    Update copy, button action, or replace the hero image.
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
            <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Current Image & Option to Replace --}}
                <div class="form-field">
                    <label class="field-label">Current Slide Image</label>
                    <div style="margin-bottom: 12px; background: #0f172a; padding: 10px; border-radius: 8px; text-align: center; max-width: 320px;">
                        <img id="currentImageDisplay" src="{{ $slider->imageUrl() }}" alt="{{ $slider->title }}" style="max-height: 160px; max-width: 100%; border-radius: 6px; object-fit: contain;">
                    </div>

                    <label class="field-label" style="font-size: 12.5px; color: #64748b;">
                        Upload Replacement Image (Leave blank to keep current image)
                    </label>
                    <input type="file" name="image" accept="image/*" class="form-input" style="padding: 7px 10px;" onchange="previewEditImage(this)">
                    @error('image') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                {{-- Eyebrow / Tag --}}
                <div class="form-field">
                    <label for="eyebrow" class="field-label">Eyebrow Tag (Optional Subtitle)</label>
                    <input type="text" id="eyebrow" name="eyebrow" class="form-input"
                        value="{{ old('eyebrow', $slider->eyebrow) }}" placeholder="e.g. SKOP-X LEARNING">
                </div>

                {{-- Slide Title --}}
                <div class="form-field">
                    <label for="title" class="field-label">Slide Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="title" name="title" class="form-input"
                        value="{{ old('title', $slider->title) }}" required>
                    @error('title') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                {{-- Slide Description --}}
                <div class="form-field">
                    <label for="description" class="field-label">Description Text</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $slider->description) }}</textarea>
                </div>

                {{-- Button CTA & URL --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-field">
                        <label for="button_text" class="field-label">Button Text</label>
                        <input type="text" id="button_text" name="button_text" class="form-input"
                            value="{{ old('button_text', $slider->button_text) }}">
                    </div>
                    <div class="form-field">
                        <label for="button_url" class="field-label">Button URL</label>
                        <input type="text" id="button_url" name="button_url" class="form-input"
                            value="{{ old('button_url', $slider->button_url) }}">
                    </div>
                </div>

                {{-- Order & Active Status --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: center; margin-top: 10px;">
                    <div class="form-field" style="margin-bottom: 0;">
                        <label for="order" class="field-label">Display Order</label>
                        <input type="number" id="order" name="order" class="form-input"
                            value="{{ old('order', $slider->order) }}" min="0">
                    </div>

                    <div style="padding-top: 18px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb;">
                            <span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">Enable slide (Live on homepage)</span>
                        </label>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.sliders.index') }}" class="btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn-submit-slide">
                        <span>Save Changes</span>
                        <span>→</span>
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function previewEditImage(input) {
            const preview = document.getElementById('currentImageDisplay');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
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

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
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
