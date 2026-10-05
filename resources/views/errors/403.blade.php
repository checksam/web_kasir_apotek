<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Akses Ditolak | PharmaPOS</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; background: #f4f7f8; color: #263238; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; padding: 24px; }
        main { width: min(100%, 480px); padding: 40px; border: 1px solid #dce5e8; border-radius: 12px; background: #fff; text-align: center; }
        .code { margin: 0; color: #006680; font-size: 14px; font-weight: 700; letter-spacing: 0.08em; }
        h1 { margin: 12px 0; font-size: 28px; }
        .message { margin: 0; color: #52636a; line-height: 1.6; }
        a { display: inline-flex; margin-top: 24px; padding: 11px 16px; border-radius: 7px; background: #006680; color: #fff; font-weight: 600; text-decoration: none; }
        a:focus-visible { outline: 3px solid #73c3d3; outline-offset: 3px; }
        @media (max-width: 480px) { main { padding: 30px 22px; } h1 { font-size: 24px; } }
    </style>
</head>
<body>
    <main aria-labelledby="page-title">
        <p class="code">ERROR 403</p>
        <h1 id="page-title">Akses Ditolak</h1>
        <p class="message">Role akun Anda tidak memiliki izin untuk membuka halaman ini.</p>

        @if (auth()->check() && auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}">Kembali ke dashboard</a>
        @elseif (auth()->check() && auth()->user()->hasRole('kasir'))
            <a href="{{ route('kasir.dashboard') }}">Kembali ke dashboard</a>
        @else
            <a href="{{ route('login') }}">Kembali ke login</a>
        @endif
    </main>
</body>
</html>