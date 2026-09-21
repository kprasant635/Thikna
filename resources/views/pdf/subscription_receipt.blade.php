<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Receipt — {{ $payment->order_reference }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #ffffff;
            padding: 30px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f5e2e;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            color: #0f5e2e;
            letter-spacing: 0.5px;
        }
        .brand-subtitle {
            font-size: 11px;
            color: #64748b;
        }
        .receipt-title-box {
            text-align: right;
        }
        .receipt-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-weight: bold;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-cell {
            vertical-align: top;
            width: 50%;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            margin-right: 10px;
        }
        .info-card-right {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            margin-left: 10px;
        }
        .info-heading {
            font-size: 11px;
            font-weight: bold;
            color: #0f5e2e;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .info-row {
            margin-bottom: 4px;
            font-size: 11.5px;
        }
        .info-label {
            color: #64748b;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #0f5e2e;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            padding: 8px 12px;
            text-align: left;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11.5px;
        }
        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .strikethrough {
            text-decoration: line-through;
            color: #94a3b8;
        }
        .discount-text {
            color: #15803d;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #0f5e2e;
            border-bottom: 2px solid #0f5e2e;
            font-weight: bold;
            font-size: 13px;
            background: #f0fdf4 !important;
            color: #0f5e2e;
        }
        .courses-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }
        .course-tag {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10.5px;
            margin: 2px 4px 2px 0;
        }
        .footer-note {
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            font-size: 10.5px;
            color: #64748b;
        }
        .stamp-box {
            display: inline-block;
            border: 2px solid #15803d;
            color: #15803d;
            font-weight: bold;
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header Table -->
    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">SKOP-X</div>
                <div class="brand-subtitle">Official Payment Receipt &amp; Tax Invoice</div>
            </td>
            <td class="receipt-title-box">
                <div class="receipt-badge">✓ PAYMENT SUCCESSFUL</div>
                <div style="font-size: 11px; color: #64748b;">Date: {{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</div>
            </td>
        </tr>
    </table>

    <!-- Info Section -->
    <table class="info-table">
        <tr>
            <td class="info-cell">
                <div class="info-card">
                    <div class="info-heading">Billed To</div>
                    <div class="info-row"><strong>{{ $user->name }}</strong></div>
                    <div class="info-row"><span class="info-label">Mobile:</span> +91 {{ $user->phone }}</div>
                    @if($user->email)
                        <div class="info-row"><span class="info-label">Email:</span> {{ $user->email }}</div>
                    @endif
                    @if($user->address)
                        <div class="info-row"><span class="info-label">Address:</span> {{ $user->address }}</div>
                    @endif
                </div>
            </td>
            <td class="info-cell">
                <div class="info-card-right">
                    <div class="info-heading">Payment Details</div>
                    <div class="info-row"><span class="info-label">Order Ref:</span> <strong>{{ $payment->order_reference }}</strong></div>
                    <div class="info-row"><span class="info-label">Txn ID:</span> {{ $payment->gateway_transaction_id ?? 'TXN-ONLINE-VERIFIED' }}</div>
                    <div class="info-row"><span class="info-label">Payment Method:</span> Online Payment Gateway</div>
                    <div class="info-row"><span class="info-label">Account Status:</span> <strong style="color: #15803d;">Active Member</strong></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Breakdown Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Course MRP</td>
                <td class="text-right strikethrough">{{ $pricing['formatted']['course_mrp'] }}</td>
            </tr>
            <tr>
                <td>Special Subscription Discount</td>
                <td class="text-right discount-text">{{ $pricing['formatted']['discount'] }}</td>
            </tr>
            <tr>
                <td><strong>Discounted Course Fee</strong></td>
                <td class="text-right"><strong>{{ $pricing['formatted']['discounted_course_fee'] }}</strong></td>
            </tr>
            <tr>
                <td>Platform Fee</td>
                <td class="text-right">{{ $pricing['formatted']['platform_fee'] }}</td>
            </tr>
            <tr>
                <td>GST @ {{ $pricing['formatted']['gst_rate'] }} on Course Fee</td>
                <td class="text-right">{{ $pricing['formatted']['gst_amount'] }}</td>
            </tr>
            <tr>
                <td>Payment Gateway Charge @ {{ $pricing['formatted']['gateway_rate'] }}</td>
                <td class="text-right">{{ $pricing['formatted']['gateway_charge'] }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL AMOUNT PAID</td>
                <td class="text-right">{{ $pricing['formatted']['total_payable'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Selected Courses Summary -->
    @if(isset($subscription) && $subscription->products->count() > 0)
        <div class="courses-box">
            <div class="info-heading">Activated Subscription Courses ({{ $subscription->products->count() }})</div>
            <div>
                @foreach($subscription->products as $product)
                    <span class="course-tag">{{ $product->icon }} {{ $product->name }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer-note">
        <p>This is a computer-generated tax receipt and does not require a physical signature.</p>
        <p style="margin-top: 4px;">Thank you for subscribing to <strong>SKOP-X Learning Program</strong>!</p>
        <div class="stamp-box">VERIFIED &amp; AUTHORIZED</div>
    </div>

</body>
</html>
