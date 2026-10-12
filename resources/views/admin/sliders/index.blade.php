@extends('layouts.admin')

@section('title', 'Landing Page Sliders — SKOP-X Admin')
@section('breadcrumb', 'Settings / Landing Page Sliders')

@section('content')

    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <span>🖼️</span>
                <span>Landing Page Hero Sliders</span>
            </h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                Upload, edit, reorder, and remove hero banner slides displayed on the public landing page.
            </p>
        </div>

        <a href="{{ route('admin.sliders.create') }}" class="btn-create-slide">
            <span>➕</span>
            <span>Upload New Slide</span>
        </a>
    </div>

    {{-- Flash Alerts --}}
    @if (session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
            <span>✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
            <span>⚠️</span>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- Slides Grid / Table Container --}}
    <div class="sliders-panel">
        <div class="sliders-panel-header">
            <div style="font-size: 16px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span>🎞️</span>
                <span>Active Hero Slides ({{ $sliders->count() }})</span>
            </div>
            <div style="font-size: 13px; color: #64748b;">
                Slides automatically cycle on the homepage
            </div>
        </div>

        @if ($sliders->count() > 0)
            <div class="slides-list">
                @foreach ($sliders as $index => $slider)
                    <div class="slide-card {{ $slider->is_active ? 'active-card' : 'disabled-card' }}">
                        {{-- Slide Image Thumbnail --}}
                        <div class="slide-preview-box">
                            <img src="{{ $slider->imageUrl() }}" alt="{{ $slider->title }}" class="slide-thumb-img">
                            <div class="slide-order-badge" title="Display Order">
                                #{{ $slider->order }}
                            </div>
                            @if ($slider->is_active)
                                <div class="slide-status-badge status-live">Live on Landing Page</div>
                            @else
                                <div class="slide-status-badge status-hidden">Hidden / Disabled</div>
                            @endif
                        </div>

                        {{-- Slide Content Details --}}
                        <div class="slide-info-box">
                            @if ($slider->eyebrow)
                                <div class="slide-eyebrow-tag">{{ $slider->eyebrow }}</div>
                            @endif
                            <h3 class="slide-title-text">{{ $slider->title }}</h3>
                            <p class="slide-desc-text">
                                {{ $slider->description ?: 'No description provided.' }}
                            </p>

                            <div class="slide-meta-row">
                                <div class="slide-btn-info">
                                    <span>CTA Button:</span>
                                    <strong>{{ $slider->button_text }}</strong>
                                    <code>{{ $slider->button_url }}</code>
                                </div>
                                <div class="slide-date-info">
                                    Last updated: {{ $slider->updated_at->format('d M Y, h:i A') }}
                                </div>
                            </div>
                        </div>

                        {{-- Slide Action Controls --}}
                        <div class="slide-actions-box">
                            <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn-action btn-edit" title="Edit this slide">
                                ✏️ Edit Slide
                            </a>

                            <form method="POST" action="{{ route('admin.sliders.toggle', $slider) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action {{ $slider->is_active ? 'btn-disable' : 'btn-enable' }}">
                                    {{ $slider->is_active ? '👁️ Disable' : '✓ Enable' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" style="margin: 0;"
                                onsubmit="return confirm('Are you sure you want to remove this slide? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete this slide">
                                    🗑️ Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center; color: #64748b;">
                <div style="font-size: 40px; margin-bottom: 12px;">🖼️</div>
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">No Hero Slides Found</h3>
                <p style="font-size: 14px; margin-bottom: 20px;">Upload your first banner slide to display it on the public landing page.</p>
                <a href="{{ route('admin.sliders.create') }}" class="btn-create-slide" style="display: inline-flex;">
                    Upload First Slide
                </a>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .btn-create-slide {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 9px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transition: all 0.15s;
        }

        .btn-create-slide:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
        }

        .sliders-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .sliders-panel-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .slides-list {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .slide-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: grid;
            grid-template-columns: 200px 1fr 160px;
            gap: 20px;
            align-items: center;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .slide-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .slide-card.disabled-card {
            background: #f8fafc;
            opacity: 0.75;
        }

        .slide-preview-box {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            height: 120px;
            background: #0f172a;
            border: 1px solid #cbd5e1;
        }

        .slide-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .slide-order-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: rgba(15, 23, 42, 0.85);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
        }

        .slide-status-badge {
            position: absolute;
            bottom: 6px;
            left: 6px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .status-live {
            background: #15803d;
            color: #ffffff;
        }

        .status-hidden {
            background: #64748b;
            color: #ffffff;
        }

        .slide-info-box {
            overflow: hidden;
        }

        .slide-eyebrow-tag {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }

        .slide-title-text {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .slide-desc-text {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .slide-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
            flex-wrap: wrap;
        }

        .slide-btn-info code {
            background: #f1f5f9;
            padding: 1px 5px;
            border-radius: 4px;
            color: #0f172a;
        }

        .slide-actions-box {
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-left: 1px solid #f1f5f9;
            padding-left: 16px;
        }

        .btn-action {
            width: 100%;
            padding: 7px 12px;
            border-radius: 7px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.15s;
            border: 1px solid transparent;
            display: inline-block;
        }

        .btn-edit {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .btn-edit:hover { background: #e2e8f0; }

        .btn-disable {
            background: #fff7ed;
            color: #c2410c;
            border-color: #fed7aa;
        }
        .btn-disable:hover { background: #ffedd5; }

        .btn-enable {
            background: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }
        .btn-enable:hover { background: #bbf7d0; }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }
        .btn-delete:hover { background: #fecaca; }

        @media (max-width: 900px) {
            .slide-card {
                grid-template-columns: 1fr;
            }
            .slide-preview-box {
                height: 160px;
            }
            .slide-actions-box {
                border-left: none;
                padding-left: 0;
                border-top: 1px solid #f1f5f9;
                padding-top: 12px;
                flex-direction: row;
            }
        }
    </style>
@endpush
