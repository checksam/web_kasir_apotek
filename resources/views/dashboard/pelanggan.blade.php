<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan | PharmaPOS</title>
    <style>
        :root { color-scheme: light; font-family: Arial, sans-serif; background: #f4f7f8; color: #263238; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; padding: 24px; }
        main { width: min(100%, 560px); padding: 36px; border: 1px solid #dce5e8; border-radius: 10px; background: #fff; }
        p { color: #52636a; line-height: 1.6; }
        .role { color: #006680; font-size: 13px; font-weight: 700; text-transform: uppercase; }
        a { display: inline-flex; margin-top: 12px; padding: 10px 15px; border-radius: 6px; background: #006680; color: #fff; font-weight: 600; text-decoration: none; }
        @media (max-width: 480px) { main { padding: 26px 22px; } }
    </style>
</head>
<body>
    <main>
        <p class="role">Pelanggan</p>
        <h1>Dashboard Pelanggan</h1>
        <p>Anda masuk sebagai {{ auth()->user()->name }}.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </main>
</body>
</html>