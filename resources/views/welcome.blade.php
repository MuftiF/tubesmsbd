<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Sipirok Indah - Sistem Absensi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #e5e7eb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border: 1px solid rgba(0,0,0,0.06);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 320px;
            text-align: center;
        }
        .logo-wrap {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: white;
            border: 1px solid #f3f4f6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            overflow: hidden;
        }
        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }
        h1 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2c5e4e;
            margin: 0 0 4px;
        }
        .subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0 0 2px;
        }
        .sub2 {
            font-size: 0.7rem;
            color: #9ca3af;
            margin: 0;
        }
        .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 1.5rem 0;
        }
        .btn-primary {
            display: block;
            width: 100%;
            background: #2c5e4e;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 0.625rem;
            box-sizing: border-box;
        }
        .btn-primary:hover {
            background: #1f4a3d;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44,94,78,0.25);
        }
        .btn-secondary {
            display: block;
            width: 100%;
            background: white;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #1f2937;
        }
        .footer {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 1.25rem;
        }
    </style>
</head>
<body>

    <div class="card">

        <div class="logo-wrap">
            <img src="{{ asset('images/LOGO.jpg') }}"
                 alt="Logo PT. Sipirok Indah"
                 onerror="this.src='https://placehold.co/72x72?text=SP'">
        </div>

        <h1>PT. Sipirok Indah</h1>
        <p class="subtitle">Sistem Absensi Karyawan</p>
        <p class="sub2">Portal resmi untuk karyawan dan pengunjung</p>

        <div class="divider"></div>

        <a href="{{ route('login') }}" class="btn-primary">Masuk ke Sistem</a>
        <a href="{{ url('/home') }}" class="btn-secondary">Lihat Homepage</a>

    </div>

    <p class="footer">© {{ date('Y') }} PT. Sipirok Indah. All rights reserved.</p>

</body>
</html>