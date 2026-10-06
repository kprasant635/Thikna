@extends('layouts.app')

@section('title', 'Official Certificate — SkopX')

@push('styles')
    <style>
        @page {
            size: landscape;
            margin: 0;
        }

        /* Certificate preview container */
        .cert-page-wrapper {
            width: 100%;
            max-width: 1120px;
            margin: 30px auto 60px;
            padding: 0 20px;
        }

        /* =========================
       CERTIFICATE CANVAS
    ========================= */

        .certificate-card {
            position: relative;

            width: 1120px;
            height: 756px;

            max-width: 100%;

            margin: 0 auto;

            overflow: hidden;

            box-sizing: border-box;

            background:
                radial-gradient(circle at 50% 50%,
                    rgba(255, 255, 255, .95),
                    rgba(247, 237, 218, .98));

            border: 1px solid #c79b55;

            color: #33200f;

            box-shadow: 0 20px 50px rgba(0, 0, 0, .12);
        }


        /* =========================
       SCALE CONTENT
    ========================= */

        .certificate-inner {
            position: absolute;

            width: 1120px;
            height: 756px;

            left: 0;
            top: 0;

            transform-origin: top left;
        }


        /* =========================
       BORDERS
    ========================= */

        .certificate-card::before {
            content: '';

            position: absolute;

            inset: 18px;

            border: 2px solid #a87532;

            pointer-events: none;

            z-index: 20;
        }

        .certificate-card::after {
            content: '';

            position: absolute;

            inset: 27px;

            border: 1px solid rgba(168, 117, 50, .65);

            pointer-events: none;

            z-index: 20;
        }


        /* =========================
       CORNERS
    ========================= */

        .cert-corner {
            position: absolute;

            width: 150px;
            height: 150px;

            z-index: 2;

            pointer-events: none;
        }

        .cert-corner.top-left {
            top: 0;
            left: 0;

            background:
                linear-gradient(135deg,
                    #6d421c 0 28%,
                    #9c682f 29% 38%,
                    transparent 39%);
        }

        .cert-corner.bottom-right {
            right: 0;
            bottom: 0;

            transform: rotate(180deg);

            background:
                linear-gradient(135deg,
                    #6d421c 0 28%,
                    #9c682f 29% 38%,
                    transparent 39%);
        }


        /* =========================
       LOGO
    ========================= */

        .cert-logo {
            position: absolute;

            top: 45px;
            left: 90px;

            width: 285px;

            z-index: 5;
        }

        .cert-logo img {
            width: 100%;
            height: auto;
            display: block;
        }


        /* =========================
       TAGLINE
    ========================= */

        .cert-tagline {
            position: absolute;

            top: 54px;
            right: 95px;

            text-align: center;

            color: #422d1d;

            font-size: 19px;

            line-height: 1.35;

            letter-spacing: 4px;

            text-transform: uppercase;

            z-index: 5;
        }

        .cert-tagline::after {
            content: '';

            display: block;

            width: 85px;
            height: 2px;

            background: #a87532;

            margin: 9px auto 0;
        }


        /* =========================
       TITLE
    ========================= */

        .cert-main-title {
            position: absolute;

            top: 135px;
            left: 300px;

            width: 570px;

            text-align: center;

            z-index: 5;
        }

        .cert-main-title h1 {
            margin: 0;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 62px;

            font-weight: 500;

            letter-spacing: 5px;

            line-height: 1;

            color: #3c2414;

            text-transform: uppercase;
        }

        .cert-main-title .subtitle {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 18px;

            margin-top: 12px;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 20px;

            letter-spacing: 6px;

            color: #4b3424;

            text-transform: uppercase;
        }

        .cert-main-title .subtitle::before,
        .cert-main-title .subtitle::after {
            content: '';

            width: 65px;
            height: 1px;

            background: #9c7547;
        }


        /* =========================
       PHOTO
    ========================= */

        .cert-photo {
            position: absolute;

            top: 202px;
            left: 65px;

            width: 193px;
            height: 264px;

            border: 3px solid #b7833f;

            background: #eee;

            overflow: hidden;

            z-index: 5;
        }

        .cert-photo img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;
        }


        /* =========================
       SEAL
    ========================= */

        .cert-seal {
            position: absolute;

            top: 157px;
            right: 68px;

            width: 150px;
            height: 150px;

            z-index: 5;
        }

        .cert-seal img {
            width: 100%;
            height: 100%;

            object-fit: contain;
        }


        /* =========================
       MAIN CONTENT
    ========================= */

        .cert-content {
            position: absolute;

            top: 275px;
            left: 285px;
            width: 550px;

            text-align: center;

            z-index: 5;
        }

        .cert-content .present {
            font-size: 17px;

            margin-bottom: 12px;

            color: #33271e;
        }

        .cert-user-name {
            display: inline-block;

            min-width: 300px;

            padding: 0 25px 7px;

            border-bottom: 2px solid #765333;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 37px;

            line-height: 1.1;

            font-weight: 700;

            color: #3b2415;

            margin-bottom: 12px;
        }

        .cert-content .statement {
            font-size: 16px;

            color: #302820;

            margin-bottom: 5px;
        }

        .cert-course-title {
            font-family: Georgia, 'Times New Roman', serif;

            font-size: 21px;

            line-height: 1.25;

            font-weight: 700;

            color: #3d2413;

            margin-bottom: 7px;
        }

        .cert-registration {
            font-size: 15px;

            color: #33271e;

            margin-bottom: 12px;
        }

        .cert-description {
            font-size: 15px;

            line-height: 1.4;

            color: #3d352e;

            max-width: 550px;

            margin: auto;
        }


        /* =========================
       DATE
    ========================= */

        .cert-date {
            position: absolute;

            left: 82px;
            bottom: 105px;

            width: 165px;

            text-align: center;

            z-index: 5;

            color: #30271f;
        }

        .cert-date .date {
            font-size: 15px;

            padding-bottom: 7px;

            border-bottom: 1px solid #5f4931;
        }

        .cert-date .label {
            font-size: 12px;

            margin-top: 5px;
        }


        /* =========================
       SIGNATURE
    ========================= */

        .cert-signature {
            position: absolute;

            bottom: 98px;
            left: 560px;

            width: 200px;

            text-align: center;

            z-index: 5;
        }

        .cert-signature-image {
            height: 50px;

            display: flex;

            align-items: center;
            justify-content: center;
        }

        .cert-signature-image img {
            max-width: 145px;
            max-height: 50px;

            object-fit: contain;
        }

        .cert-signature-line {
            border-top: 1px solid #59432c;

            padding-top: 5px;

            font-size: 12px;
        }

        .cert-signature-name {
            font-size: 15px;

            font-weight: 700;

            margin-top: 3px;
        }


        /* =========================
       QR
    ========================= */

        .cert-qr {
            position: absolute;

            right: 82px;
            bottom: 88px;

            width: 105px;

            text-align: center;

            z-index: 5;
        }

        .cert-qr img {
            width: 100px;
            height: 100px;

            display: block;

            margin: auto;

            background: white;

            padding: 4px;

            box-sizing: border-box;
        }

        .cert-qr-label {
            font-size: 11px;

            margin-top: 4px;

            color: #3f3429;
        }


        /* =========================
       BOTTOM ITEMS
    ========================= */

        .cert-bottom-values {
            position: absolute;

            bottom: 35px;
            left: 50%;

            transform: translateX(-50%);

            display: flex;

            justify-content: center;
            align-items: center;

            gap: 20px;

            color: #71491f;

            font-size: 11px;

            letter-spacing: 1px;

            z-index: 5;
        }

        .cert-bottom-values .item {
            display: flex;

            align-items: center;

            gap: 5px;

            white-space: nowrap;
        }

        .cert-bottom-values .icon {
            font-size: 18px;
        }

        .cert-bottom-motto {
            position: absolute;

            bottom: 10px;
            left: 50%;

            transform: translateX(-50%);

            color: #71491f;

            font-size: 10px;

            letter-spacing: 3px;

            white-space: nowrap;

            z-index: 5;
        }

        @media (max-width: 1140px) {

            .cert-page-wrapper {
                overflow-x: auto;
            }

            .certificate-card {
                width: 100%;
                height: auto;
                aspect-ratio: 1120 / 756;
            }

            .certificate-inner {
                transform: scale(calc((100vw - 40px) / 1120));

                transform-origin: top left;
            }
        }

        .cert-content {
            top: 275px;
        }

        .cert-signature {
            bottom: 121px;
        }

        .cert-qr {
            bottom: 112px;
        }

        .cert-page-wrapper {
            max-width: 1120px;
            margin: 30px auto 60px;
            padding: 0 20px;
        }

        .cert-actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: #fff;
            padding: 16px 24px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
            flex-wrap: wrap;
            gap: 12px;
        }

        /* =========================
           CERTIFICATE
        ========================= */

        .certificate-card {
            position: relative;
            width: 100%;
            aspect-ratio: 1.48 / 1;

            overflow: hidden;

            background:
                radial-gradient(circle at 50% 50%,
                    rgba(255, 255, 255, .9),
                    rgba(247, 237, 218, .95));

            color: #33200f;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, .15);

            border: 1px solid #c79b55;
        }

        /* Outer gold border */
        .certificate-card::before {
            content: '';
            position: absolute;
            inset: 18px;

            border: 2px solid #a87532;

            pointer-events: none;
            z-index: 20;
        }

        /* Inner gold border */
        .certificate-card::after {
            content: '';
            position: absolute;
            inset: 27px;

            border: 1px solid rgba(168, 117, 50, .65);

            pointer-events: none;
            z-index: 20;
        }


        /* =========================
           DECORATIVE CORNERS
        ========================= */

        .cert-corner {
            position: absolute;
            width: 150px;
            height: 150px;
            z-index: 2;
            pointer-events: none;
        }

        .cert-corner.top-left {
            top: 0;
            left: 0;

            background:
                linear-gradient(135deg,
                    #6d421c 0 28%,
                    #9c682f 29% 38%,
                    transparent 39%);
        }

        .cert-corner.bottom-right {
            right: 0;
            bottom: 0;

            transform: rotate(180deg);

            background:
                linear-gradient(135deg,
                    #6d421c 0 28%,
                    #9c682f 29% 38%,
                    transparent 39%);
        }


        /* =========================
           TOP HEADER
        ========================= */

        .cert-logo {
            position: absolute;

            top: 45px;
            left: 90px;

            width: 285px;

            z-index: 5;
        }

        .cert-logo img {
            width: 100%;
            height: auto;
            display: block;
        }

        .cert-tagline {
            position: absolute;

            top: 54px;
            right: 95px;

            text-align: center;

            color: #422d1d;

            font-size: 19px;
            line-height: 1.35;

            letter-spacing: 4px;

            text-transform: uppercase;

            z-index: 5;
        }

        .cert-tagline::after {
            content: '';

            display: block;

            width: 85px;
            height: 2px;

            background: #a87532;

            margin: 9px auto 0;
        }


        /* =========================
           MAIN TITLE
        ========================= */

        .cert-main-title {
            position: absolute;

            top: 138px;
            left: 50%;

            transform: translateX(-50%);

            width: 65%;

            text-align: center;

            z-index: 5;
        }

        .cert-main-title h1 {
            margin: 0;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: clamp(35px, 5vw, 66px);

            font-weight: 500;

            letter-spacing: 6px;

            color: #3c2414;

            text-transform: uppercase;
        }

        .cert-main-title .subtitle {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 20px;

            margin-top: 3px;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 22px;

            letter-spacing: 7px;

            color: #4b3424;

            text-transform: uppercase;
        }

        .cert-main-title .subtitle::before,
        .cert-main-title .subtitle::after {
            content: '';

            width: 75px;
            height: 1px;

            background: #9c7547;
        }


        /* =========================
           PHOTO
        ========================= */

        .cert-photo {
            position: absolute;

            top: 202px;
            left: 65px;

            width: 193px;
            height: 264px;

            border: 3px solid #b7833f;

            background: #eee;

            overflow: hidden;

            z-index: 5;
        }

        .cert-photo img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;
        }


        /* =========================
           SEAL
        ========================= */

        .cert-seal {
            position: absolute;

            top: 157px;
            right: 68px;

            width: 150px;
            height: 150px;

            z-index: 5;
        }

        .cert-seal img {
            width: 100%;
            height: 100%;

            object-fit: contain;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .cert-content {
            position: absolute;

            top: 275px;
            left: 280px;
            right: 245px;

            text-align: center;

            z-index: 5;
        }

        .cert-content .present {
            font-size: 19px;

            margin-bottom: 15px;

            color: #33271e;
        }

        .cert-user-name {
            display: inline-block;

            min-width: 300px;

            padding: 0 25px 5px;

            border-bottom: 2px solid #765333;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 39px;

            font-weight: 700;

            color: #3b2415;

            margin-bottom: 13px;
        }

        .cert-content .statement {
            font-size: 18px;

            color: #302820;

            margin-bottom: 5px;
        }

        .cert-course-title {
            font-family: Georgia, 'Times New Roman', serif;

            font-size: 24px;

            font-weight: 700;

            color: #3d2413;

            margin-bottom: 7px;
        }

        .cert-registration {
            font-size: 17px;

            color: #33271e;

            margin-bottom: 15px;
        }

        .cert-registration strong {
            font-weight: 700;
        }

        .cert-description {
            font-size: 17px;

            line-height: 1.4;

            color: #3d352e;

            max-width: 600px;

            margin: auto;
        }


        /* =========================
           COURSE TAGS
        ========================= */

        .cert-courses-list {
            display: flex;

            justify-content: center;

            flex-wrap: wrap;

            gap: 7px;

            margin-top: 8px;
        }

        .cert-course-tag {
            font-size: 12px;

            padding: 4px 10px;

            border: 1px solid #bd9250;

            background: rgba(255, 248, 230, .8);

            color: #5a3b1e;

            border-radius: 3px;
        }


        /* =========================
           DATE
        ========================= */

        .cert-date {
            position: absolute;

            left: 82px;
            bottom: 126px;

            width: 165px;

            text-align: center;

            z-index: 5;

            color: #30271f;
        }

        .cert-date .date {
            font-size: 16px;

            padding-bottom: 8px;

            border-bottom: 1px solid #5f4931;
        }

        .cert-date .label {
            font-size: 13px;

            margin-top: 6px;
        }


        /* =========================
           SIGNATURE
        ========================= */

        .cert-signature {
            position: absolute;

            bottom: 121px;
            left: 50%;

            transform: translateX(-50%);

            width: 200px;

            text-align: center;

            z-index: 5;
        }

        .cert-signature-image {
            height: 55px;

            display: flex;

            align-items: center;
            justify-content: center;
        }

        .cert-signature-image img {
            max-width: 150px;
            max-height: 55px;

            object-fit: contain;
        }

        .cert-signature-line {
            border-top: 1px solid #59432c;

            padding-top: 5px;

            font-size: 13px;
        }

        .cert-signature-name {
            font-size: 16px;

            font-weight: 700;

            margin-top: 3px;
        }


        /* =========================
           QR
        ========================= */

        .cert-qr {
            position: absolute;

            right: 83px;
            bottom: 112px;

            width: 105px;

            text-align: center;

            z-index: 5;
        }

        .cert-qr img {
            width: 100px;
            height: 100px;

            display: block;

            margin: auto;

            background: white;

            padding: 4px;
        }

        .cert-qr-label {
            font-size: 12px;

            margin-top: 4px;

            color: #3f3429;
        }


        /* =========================
           BOTTOM VALUES
        ========================= */

        .cert-bottom-values {
            position: absolute;

            bottom: 35px;
            left: 50%;

            transform: translateX(-50%);

            display: flex;

            justify-content: center;
            align-items: center;

            gap: 25px;

            color: #71491f;

            font-size: 12px;

            letter-spacing: 1px;

            z-index: 5;
        }

        .cert-bottom-values .item {
            display: flex;

            align-items: center;

            gap: 7px;

            white-space: nowrap;
        }

        .cert-bottom-values .icon {
            font-size: 22px;
        }

        .cert-bottom-motto {
            position: absolute;

            bottom: 12px;
            left: 50%;

            transform: translateX(-50%);

            color: #71491f;

            font-size: 12px;

            letter-spacing: 3px;

            white-space: nowrap;

            z-index: 5;
        }


        /* =========================
           RESPONSIVE PREVIEW
        ========================= */

        @media (max-width: 900px) {

            .certificate-card {
                min-width: 900px;
            }

            .cert-page-wrapper {
                overflow-x: auto;
            }
        }


        /* =========================
           PRINT
        ========================= */

        @media print {

            @page {
                size: landscape;
                margin: 0;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;

                background: #fff !important;
            }

            .site-header,
            .site-footer,
            .cert-actions-bar {
                display: none !important;
            }

            .cert-page-wrapper {
                max-width: none;

                margin: 0;
                padding: 0;
            }

            .certificate-card {
                width: 100vw;
                height: 100vh;

                aspect-ratio: auto;

                border: none;

                box-shadow: none;

                page-break-after: avoid;
            }
        }
    </style>
@endpush


@section('content')

    <div class="cert-page-wrapper">

        <!-- Action Bar -->
        <div class="cert-actions-bar">

            <div>
                <span style="font-weight:700;font-size:16px;color:var(--ink);">
                    Official SkopX Certificate
                </span>

                <span style="font-size:13px;color:var(--ink-soft);margin-left:10px;">
                    ID: {{ $certificate->certificate_number }}
                </span>
            </div>

            <div style="display:flex;gap:10px;">

                <button type="button" onclick="window.print()" class="btn btn-outline"
                    style="padding:9px 18px;font-size:13.5px;border-radius:6px;">
                    🖨️ Print Certificate
                </button>

                <a href="{{ route('benefits.index') }}" class="btn btn-primary"
                    style="padding:9px 18px;font-size:13.5px;border-radius:6px;">
                    Explore Member Benefits →
                </a>

            </div>

        </div>


        <!-- Certificate -->
        <div class="certificate-card">

            <div class="certificate-inner">

                <div class="cert-corner top-left"></div>

                <div class="cert-corner bottom-right"></div>

                <div class="cert-logo">
                    <img src="{{ asset('images/skopx-logo.png') }}" alt="SKOP-X Logo">
                </div>

                <div class="cert-tagline">
                    SKILL TODAY<br>
                    BETTER TOMORROW
                </div>

                <div class="cert-main-title">
                    <h1>Certificate</h1>

                    <div class="subtitle">
                        Of Completion
                    </div>
                </div>

                {{-- photo --}}
                <div class="cert-photo">
                    @if ($user->profile_photo)
                        <img src="{{ Auth::user()->profilePhotoUrl() }}" alt="{{ $user->name }}">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="{{ $user->name }}">
                    @endif
                </div>

                {{-- seal --}}
                <div class="cert-seal">
                    @if (file_exists(public_path('images/skopx-seal.png')))
                        <img src="{{ asset('images/skopx-seal.png') }}" alt="SKOP-X Seal">
                    @else
                        <div
                            style="
                    width:100%;
                    height:100%;
                    border-radius:50%;
                    background:#8b5e27;
                    border:8px solid #d3a45b;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    text-align:center;
                    color:#fff;
                    font-weight:700;
                    font-size:16px;
                ">
                            SKOP-X<br>VERIFIED
                        </div>
                    @endif
                </div>

                {{-- content --}}
                <div class="cert-content">

                    <div class="present">
                        This is to certify that
                    </div>

                    <div class="cert-user-name">
                        {{ $user->name }}
                    </div>

                    <div class="statement">
                        has successfully completed the course
                    </div>

                    @if (isset($certificate->payload['courses']) && is_array($certificate->payload['courses']))
                        <div class="cert-course-title">
                            {{ implode(', ', $certificate->payload['courses']) }}
                        </div>
                    @else
                        <div class="cert-course-title">
                            Selected Subscription Masterclasses
                        </div>
                    @endif

                    <div class="cert-registration">
                        Registration No.:
                        <strong>{{ $certificate->certificate_number }}</strong>
                    </div>

                    <div class="cert-description">
                        We appreciate your commitment to learning<br>
                        and your effort towards skill development.
                    </div>

                </div>

                {{-- date --}}
                <div class="cert-date">
                    <div class="date">
                        {{ $certificate->issued_at->format('d F Y') }}
                    </div>

                    <div class="label">
                        Date of Issue
                    </div>
                </div>

                {{-- signature --}}
                <div class="cert-signature">

                    <div class="cert-signature-image">
                        @if (file_exists(public_path('images/skopx-signature.png')))
                            <img src="{{ asset('images/skopx-signature.png') }}" alt="Authorized Signature">
                        @else
                            <div
                                style="
                        font-family:cursive;
                        font-size:34px;
                        color:#173b73;
                    ">
                                SkopX
                            </div>
                        @endif
                    </div>

                    <div class="cert-signature-line">
                        Authorized Signatory
                    </div>

                    <div class="cert-signature-name">
                        SKOP-X
                    </div>

                </div>

                {{-- QR --}}
                {{-- <div class="cert-qr">

                    @if (isset($certificate->qr_code_url))
                        <img src="{{ $certificate->qr_code_url }}" alt="Scan to Verify">
                    @else
                        <div
                            style="
                    width:100px;
                    height:100px;
                    background:#fff;
                    border:1px solid #ddd;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:11px;
                    color:#777;
                ">
                            QR
                        </div>
                    @endif

                    <div class="cert-qr-label">
                        Scan to Verify
                    </div>

                </div> --}}

                {{-- bottom --}}
                <div class="cert-bottom-values">

                    <div class="item">
                        <span class="icon">🎓</span>
                        LEARN
                    </div>

                    <div>|</div>

                    <div class="item">
                        <span class="icon">📈</span>
                        GROW
                    </div>

                    <div>|</div>

                    <div class="item">
                        <span class="icon">👥</span>
                        ACHIEVE
                    </div>

                    <div>|</div>

                    <div class="item">
                        <span class="icon">🌐</span>
                        TOGETHER
                    </div>

                </div>



            </div>

        </div>

    </div>

@endsection
