<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual ID Card — {{ $user->name }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            padding: 20px;
            color: #0f172a;
        }
        .id-card-wrapper {
            width: 320px;
            margin: 0 auto;
            border-radius: 16px;
            border: 2px solid #0f5e2e;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            position: relative;
        }
        .id-header {
            background: linear-gradient(135deg, #0f5e2e 0%, #06371a 100%);
            color: #ffffff;
            padding: 20px 16px 35px;
            text-align: center;
            position: relative;
        }
        .brand-logo {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .brand-tagline {
            font-size: 9.5px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .photo-container {
            text-align: center;
            margin-top: -30px;
            margin-bottom: 12px;
        }
        .profile-img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background: #e2e8f0;
        }
        .initials-avatar {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            border: 4px solid #ffffff;
            background: #0f5e2e;
            color: #ffffff;
            font-size: 30px;
            font-weight: bold;
            line-height: 77px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .member-details {
            padding: 0 20px 16px;
            text-align: center;
        }
        .member-name {
            font-size: 17px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .member-code-badge {
            display: inline-block;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #0f5e2e;
            font-family: monospace;
            font-weight: bold;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        .info-table {
            width: 100%;
            font-size: 11px;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-label {
            color: #64748b;
            text-align: left;
            width: 40%;
        }
        .info-val {
            font-weight: bold;
            color: #0f172a;
            text-align: right;
            width: 60%;
        }
        .status-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background: #dcfce7;
            color: #15803d;
        }
        .status-pending {
            background: #fff7ed;
            color: #c2410c;
        }
        .id-footer {
            background: #f8fafc;
            border-top: 1px dashed #cbd5e1;
            padding: 10px 15px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
        .barcode-strip {
            font-family: monospace;
            letter-spacing: 4px;
            font-size: 12px;
            color: #334155;
            margin-top: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="id-card-wrapper">
        <div class="id-header">
            <div class="brand-logo">SKOP-X</div>
            <div class="brand-tagline">Official Digital Member Pass</div>
        </div>

        <div class="photo-container">
            @if($user->profile_photo && file_exists(storage_path('app/public/' . $user->profile_photo)))
                <img src="{{ public_path('storage/' . $user->profile_photo) }}" class="profile-img" alt="Profile Photo">
            @else
                <div class="initials-avatar">{{ $user->initials() }}</div>
            @endif
        </div>

        <div class="member-details">
            <div class="member-name">{{ $user->name }}</div>
            <div class="member-code-badge">ID: {{ $user->referral_code ?? ('THK' . str_pad($user->id, 5, '0', STR_PAD_LEFT)) }}</div>

            <table class="info-table">
                <tr>
                    <td class="info-label">Mobile</td>
                    <td class="info-val">+91 {{ $user->phone }}</td>
                </tr>
                @if($user->email)
                <tr>
                    <td class="info-label">Email</td>
                    <td class="info-val">{{ $user->email }}</td>
                </tr>
                @endif
                <tr>
                    <td class="info-label">Account Status</td>
                    <td class="info-val">
                        @if($user->isActive())
                            <span class="status-pill status-active">✓ Active Member</span>
                        @else
                            <span class="status-pill status-pending">⚡ Pending</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Learning Status</td>
                    <td class="info-val">
                        @if($user->isCertified())
                            <span style="color: #15803d; font-weight: bold;">🏆 Certified</span>
                        @else
                            <span style="color: #64748b;">In Progress</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Member Since</td>
                    <td class="info-val">{{ $user->created_at->format('M Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="id-footer">
            <div>Authorized SkopX Digital Identity</div>
            <div class="barcode-strip">||| | |||| ||| |||| | |||</div>
        </div>
    </div>

</body>
</html>
