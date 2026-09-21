@extends('layouts.app')

@section('title', 'Select Subscription Products — SkopX')
@section('minimal-footer', true)

@push('styles')
    <style>
        .sub-page-wrapper {
            max-width: 1040px;
            margin: 32px auto 60px;
            padding: 0 20px;
        }

        .sub-hero {
            background: linear-gradient(135deg, var(--green-deep) 0%, #0d4623 100%);
            color: white;
            border-radius: var(--radius-lg);
            padding: 36px 40px;
            margin-bottom: 32px;
            box-shadow: 0 16px 36px rgba(15, 94, 46, 0.18);
            position: relative;
            overflow: hidden;
        }

        .sub-hero::after {
            content: '🎉';
            font-size: 140px;
            position: absolute;
            right: 20px;
            bottom: -30px;
            opacity: 0.15;
            pointer-events: none;
        }

        .sub-hero h1 {
            font-family: var(--font-heading, 'Fraunces', serif);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .sub-hero p {
            font-size: 16px;
            opacity: 0.92;
            max-width: 650px;
            line-height: 1.5;
            margin-bottom: 6px;
        }

        .sub-hero .sub-hero-note {
            font-size: 14px;
            color: #f6e05e;
            font-weight: 600;
        }

        .sub-sticky-bar {
            position: sticky;
            top: 20px;
            z-index: 90;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid var(--line);
            border-radius: var(--radius-md);
            padding: 16px 24px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            gap: 16px;
            flex-wrap: wrap;
        }

        .counter-box {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .counter-badge {
            padding: 8px 18px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .counter-badge.incomplete {
            background: #fffaf0;
            border: 1.5px solid #dd6b20;
            color: #c05621;
        }

        .counter-badge.complete {
            background: #f0fff4;
            border: 1.5px solid #38a169;
            color: #276749;
        }

        .counter-hint {
            font-size: 13.5px;
            color: var(--ink-soft);
        }

        .fee-summary {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .fee-amount {
            font-size: 18px;
            font-weight: 700;
            color: var(--green-deep);
        }

        .btn-continue {
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 700;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-continue:disabled {
            background: #e2e8f0;
            color: #a0aec0;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-continue:not(:disabled) {
            background: var(--green-deep);
            color: white;
            box-shadow: 0 4px 14px rgba(15, 94, 46, 0.3);
        }

        .btn-continue:not(:disabled):hover {
            background: #0b4822;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(15, 94, 46, 0.38);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 40px;
        }

        .product-card {
            background: var(--card);
            border: 2px solid var(--line);
            border-radius: var(--radius-md);
            padding: 20px 18px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
        }

        .product-card:hover {
            border-color: var(--green-tint);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .product-card.selected {
            border-color: var(--green-deep);
            background: #f4faf6;
            box-shadow: 0 0 0 3px rgba(15, 94, 46, 0.15);
        }

        .product-card .check-icon {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #cbd5e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            transition: all 0.2s ease;
        }

        .product-card.selected .check-icon {
            background: var(--green-deep);
            border-color: var(--green-deep);
        }

        .product-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .product-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.35;
            margin-bottom: 4px;
        }

        .product-category {
            font-size: 12px;
            color: var(--ink-soft);
            font-weight: 500;
        }

        /* Modal Styling */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }

        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 32px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }

        .modal-backdrop.active .modal-card {
            transform: scale(1);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .modal-header .shield-icon {
            font-size: 44px;
            margin-bottom: 8px;
        }

        .modal-header h2 {
            font-size: 22px;
            color: var(--green-deep);
            margin-bottom: 4px;
        }

        .modal-header p {
            font-size: 13.5px;
            color: var(--ink-soft);
        }

        .order-details {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 18px;
            margin-bottom: 24px;
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .order-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px dashed var(--line);
            font-weight: 700;
            font-size: 16px;
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-pay-success {
            width: 100%;
            padding: 13px;
            background: var(--green-deep);
            color: white;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
        }

        .btn-pay-cancel {
            width: 100%;
            padding: 11px;
            background: transparent;
            color: #e53e3e;
            font-weight: 600;
            font-size: 14px;
            border: 1.5px solid #feb2b2;
            border-radius: var(--radius-sm);
            cursor: pointer;
        }

        /* Fee Breakdown Component */
        .fee-breakdown-card {
            background: #ffffff;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-md);
            padding: 22px 26px;
            margin-bottom: 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .fee-breakdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1.5px dashed var(--line);
        }

        .fee-breakdown-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--green-deep);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fee-tag {
            font-size: 12px;
            font-weight: 600;
            background: #e6fffa;
            color: #234e52;
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid #b2f5ea;
        }

        .fee-breakdown-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--ink);
        }

        .fee-row.mrp-row .fee-val {
            text-decoration: line-through;
            color: var(--ink-soft);
        }

        .fee-row.discount-row {
            color: #276749;
            font-weight: 600;
        }

        .fee-row.discounted-fee-row {
            font-weight: 700;
            color: var(--ink);
            padding-bottom: 4px;
        }

        .fee-row.total-row {
            padding-top: 14px;
            border-top: 2px solid var(--line);
            margin-top: 6px;
            font-weight: 800;
            font-size: 16px;
            color: var(--green-deep);
        }

        .fee-row.total-row .fee-val {
            font-size: 20px;
            color: var(--green-deep);
        }

        .fee-divider-light {
            border-top: 1px dashed var(--line);
            margin: 4px 0;
        }

        @media (max-width: 640px) {
            .sub-hero {
                padding: 24px 20px;
            }

            .sub-hero h1 {
                font-size: 24px;
            }

            .sub-sticky-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .counter-box,
            .fee-summary {
                justify-content: space-between;
                width: 100%;
            }

            .btn-continue {
                width: 100%;
                justify-content: center;
            }

            .fee-breakdown-card {
                padding: 18px 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="sub-page-wrapper">

        <!-- Welcome Hero Banner -->
        <div class="sub-hero">
            <h1>Welcome to SKOP-X! 🎉</h1>
            <p>Your account has been created successfully.</p>
            <p class="sub-hero-note">Choose at least 1 course below and complete the
                {{ $pricing['formatted']['total_payable'] }} payment to activate your account
                and unlock member benefits.</p>
        </div>

        @if (session('warning'))
            <div
                style="background: #fffaf0; border: 1px solid #fbd38d; color: #c05621; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 10px;">
                <span>⚠️</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div
                style="background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 14px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="productSelectionForm" action="{{ route('subscription.checkout') }}" method="POST">
            @csrf

            <!-- Sticky Selection Counter & Action Bar -->
            <div class="sub-sticky-bar">
                <div class="counter-box">
                    <div id="counterBadge"
                        class="counter-badge {{ count($selectedProductIds) >= 1 ? 'complete' : 'incomplete' }}">
                        Selected: <span id="selectedCount">{{ count($selectedProductIds) }}</span> Course(s)
                    </div>
                    <div id="counterHint" class="counter-hint">
                        @if (count($selectedProductIds) >= 1)
                            ✓ Minimum met! You can proceed.
                        @else
                            Select at least 1 course to activate
                        @endif
                    </div>
                </div>

                <div class="fee-summary">
                    <div class="fee-amount">TOTAL PAYABLE: {{ $pricing['formatted']['total_payable'] }}</div>
                    <button type="submit" id="btnContinue" class="btn-continue"
                        {{ count($selectedProductIds) >= 1 ? '' : 'disabled' }}>
                        <span>Continue to Payment</span>
                        <span>→</span>
                    </button>
                </div>
            </div>

            <!-- Fee Breakdown Box inside Selection Div -->


            <!-- 16 Subscription Products Grid -->
            <div class="products-grid">
                @foreach ($products as $product)
                    @php
                        $isSelected = in_array($product->id, $selectedProductIds);
                    @endphp
                    <div class="product-card {{ $isSelected ? 'selected' : '' }}" data-id="{{ $product->id }}">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}" id="prod_{{ $product->id }}"
                            {{ $isSelected ? 'checked' : '' }} style="display: none;" />
                        <div class="check-icon">✓</div>
                        <div class="product-icon">{{ $product->icon }}</div>
                        <div>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-category">{{ $product->category }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </form>
    </div>

    <!-- Payment Gateway Modal Simulator -->
    <div id="paymentModal" class="modal-backdrop">
        <div class="modal-card">
            <div class="modal-header">
                <div class="shield-icon">🛡️</div>
                <h2>SkopX Secure Payment</h2>
                <p>Server-Verified Payment Gateway</p>
            </div>

            <div class="order-details">
                <div class="order-row">
                    <span style="color: var(--ink-soft);">Order Reference:</span>
                    <span id="orderRefDisplay" style="font-family: monospace; font-weight: 600;">-</span>
                </div>
                <div class="order-row">
                    <span style="color: var(--ink-soft);">Account Holder:</span>
                    <span>{{ $user->name }}</span>
                </div>
                <div class="order-row">
                    <span style="color: var(--ink-soft);">Mobile:</span>
                    <span>{{ $user->phone }}</span>
                </div>

                <div class="fee-divider-light" style="margin: 10px 0;"></div>

                <div class="order-row" style="font-size: 13.5px;">
                    <span style="color: var(--ink-soft);">Course MRP:</span>
                    <span
                        style="text-decoration: line-through; color: var(--ink-soft);">{{ $pricing['formatted']['course_mrp'] }}</span>
                </div>
                <div class="order-row" style="font-size: 13.5px;">
                    <span style="color: var(--ink-soft);">Discount:</span>
                    <span style="color: #276749; font-weight: 600;">{{ $pricing['formatted']['discount'] }}</span>
                </div>
                <div class="order-row" style="font-size: 13.5px; font-weight: 600;">
                    <span>Discounted Course Fee:</span>
                    <span>{{ $pricing['formatted']['discounted_course_fee'] }}</span>
                </div>
                <div class="order-row" style="font-size: 13.5px;">
                    <span style="color: var(--ink-soft);">Platform Fee:</span>
                    <span>{{ $pricing['formatted']['platform_fee'] }}</span>
                </div>
                <div class="order-row" style="font-size: 13.5px;">
                    <span style="color: var(--ink-soft);">GST @ {{ $pricing['formatted']['gst_rate'] }} on Course
                        Fee:</span>
                    <span>{{ $pricing['formatted']['gst_amount'] }}</span>
                </div>
                <div class="order-row" style="font-size: 13.5px;">
                    <span style="color: var(--ink-soft);">Payment Gateway Charge @
                        {{ $pricing['formatted']['gateway_rate'] }}:</span>
                    <span>{{ $pricing['formatted']['gateway_charge'] }}</span>
                </div>

                <div class="order-row"
                    style="margin-top: 10px; padding-top: 10px; border-top: 2px solid var(--line); font-weight: 800; font-size: 16px;">
                    <span>TOTAL PAYABLE:</span>
                    <span
                        style="color: var(--green-deep); font-size: 18px;">{{ $pricing['formatted']['total_payable'] }}</span>
                </div>
            </div>

            <form id="paymentVerifyForm" action="{{ route('subscription.payment.verify') }}" method="POST">
                @csrf
                <input type="hidden" name="order_reference" id="inputOrderRef" value="" />
                <input type="hidden" name="status" id="inputPaymentStatus" value="success" />

                <div class="modal-actions">
                    <button type="button" id="btnPaySuccess" class="btn-pay-success">
                        Pay {{ $pricing['formatted']['total_payable'] }} &amp; Activate Account
                    </button>
                    <button type="button" id="btnPayCancel" class="btn-pay-cancel">
                        Cancel Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.product-card');
            const selectedCountSpan = document.getElementById('selectedCount');
            const counterBadge = document.getElementById('counterBadge');
            const counterHint = document.getElementById('counterHint');
            const btnContinue = document.getElementById('btnContinue');
            const form = document.getElementById('productSelectionForm');

            const paymentModal = document.getElementById('paymentModal');
            const orderRefDisplay = document.getElementById('orderRefDisplay');
            const inputOrderRef = document.getElementById('inputOrderRef');
            const inputPaymentStatus = document.getElementById('inputPaymentStatus');
            const paymentVerifyForm = document.getElementById('paymentVerifyForm');
            const btnPaySuccess = document.getElementById('btnPaySuccess');
            const btnPayCancel = document.getElementById('btnPayCancel');

            function updateSelectionState() {
                let count = 0;
                cards.forEach(card => {
                    const checkbox = card.querySelector('input[type="checkbox"]');
                    if (checkbox.checked) {
                        card.classList.add('selected');
                        count++;
                    } else {
                        card.classList.remove('selected');
                    }
                });

                selectedCountSpan.textContent = count;

                if (count >= 1) {
                    counterBadge.className = 'counter-badge complete';
                    counterHint.textContent = '✓ Minimum met! You can proceed to payment.';
                    btnContinue.disabled = false;
                } else {
                    counterBadge.className = 'counter-badge incomplete';
                    counterHint.textContent = 'Select at least 1 course to activate';
                    btnContinue.disabled = true;
                }
            }

            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    updateSelectionState();
                });
            });

            // Form submission -> AJAX checkout to save products and create order
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const selectedCheckboxes = document.querySelectorAll(
                    '.product-card input[type="checkbox"]:checked');
                if (selectedCheckboxes.length < 1) {
                    alert('Please select at least 1 course before continuing.');
                    return;
                }

                btnContinue.disabled = true;
                btnContinue.innerHTML = '<span>Processing…</span>';

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        btnContinue.disabled = false;
                        btnContinue.innerHTML = '<span>Continue to Payment</span> <span>→</span>';

                        if (data.success && data.payment) {
                            // Show payment modal with reference
                            orderRefDisplay.textContent = data.payment.order_reference;
                            inputOrderRef.value = data.payment.order_reference;
                            paymentModal.classList.add('active');
                        } else {
                            alert(data.message || 'An error occurred. Please try again.');
                        }
                    })
                    .catch(err => {
                        btnContinue.disabled = false;
                        btnContinue.innerHTML = '<span>Continue to Payment</span> <span>→</span>';
                        alert('Failed to initiate payment. Please try again.');
                    });
            });

            // Simulate Pay Success
            btnPaySuccess.addEventListener('click', function() {
                inputPaymentStatus.value = 'success';
                btnPaySuccess.disabled = true;
                btnPaySuccess.textContent = 'Verifying Payment Server-Side…';
                paymentVerifyForm.submit();
            });

            // Simulate Pay Cancel
            btnPayCancel.addEventListener('click', function() {
                if (confirm(
                        'Are you sure you want to cancel the payment? Your account will remain inactive.'
                    )) {
                    inputPaymentStatus.value = 'cancelled';
                    paymentVerifyForm.submit();
                }
            });

            // Auto open modal if returning with an existing pending order in URL query
            const urlParams = new URLSearchParams(window.location.search);
            const orderRefQuery = urlParams.get('order');
            if (orderRefQuery) {
                orderRefDisplay.textContent = orderRefQuery;
                inputOrderRef.value = orderRefQuery;
                paymentModal.classList.add('active');
            }
        });
    </script>
@endpush
