<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - siprespro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        /* Header - Green Gradient */
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-logo {
            font-size: 48px;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .email-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        /* Body */
        .email-body {
            padding: 40px 30px;
        }

        .email-body h2 {
            color: #0f172a;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .email-body p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        /* Button */
        .btn-reset {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            margin: 24px 0;
            text-align: center;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        /* Info Box */
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 16px;
            border-radius: 8px;
            margin: 24px 0;
        }

        .info-box p {
            color: #166534;
            font-size: 13px;
            margin: 0;
        }

        .info-box strong {
            color: #14532d;
        }

        /* Footer */
        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .email-footer p {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .email-footer .brand {
            color: #10b981;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }

            .email-header,
            .email-body,
            .email-footer {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="email-container">

        <!-- Header -->
        <div class="email-header">
            <div class="email-logo">🌿</div>
            <h1>siprespro</h1>
            <p>Sistem Informasi Absensi<br>Prodi Budidaya Tanaman Perkebunan</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Halo, {{ $user->email ?? 'Pengguna' }} 👋</h2>

            <p>
                Kami menerima permintaan untuk mereset password akun siprespro Anda.
                Klik tombol di bawah ini untuk membuat password baru:
            </p>

            <!-- Reset Button -->
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="btn-reset">
                    🔐 Reset Password Saya
                </a>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <p>
                    <strong>⏰ Link ini akan kedaluwarsa dalam 1 jam.</strong><br>
                    Jika Anda tidak meminta reset password, abaikan email ini atau hubungi admin jika Anda memiliki
                    kekhawatiran.
                </p>
            </div>

            <p>
                Atau salin dan tempel link berikut ke browser Anda:
            </p>

            <p
                style="background: #f1f5f9; padding: 12px; border-radius: 8px; font-size: 12px; color: #475569; word-break: break-all;">
                {{ $actionUrl }}
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>
                <span class="brand">siprespro</span> - Politeknik Negeri Jember<br>
                Jurusan Produksi Pertanian - Prodi Budidaya Tanaman Perkebunan
            </p>
            <p>
                Butuh bantuan? Hubungi <a href="mailto:admin.prodi@polije.ac.id"
                    style="color: #10b981;">admin.prodi@polije.ac.id</a>
            </p>
            <p style="margin-top: 16px; font-size: 11px;">
                &copy; {{ date('Y') }} siprespro. All rights reserved.
            </p>
        </div>

    </div>

</body>

</html>