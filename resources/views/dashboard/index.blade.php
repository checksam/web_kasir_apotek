<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PharmaPOS</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #e7e7e7;
            font-family: Arial, sans-serif;
            color: #1c2c34;
        }
        .layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #2d3741;
            color: white;
            padding: 18px 0 20px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px 18px;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-icon svg { width: 18px; height: 18px; fill: white; }
        .brand-text {
            font-size: 12px;
            font-weight: 700;
            line-height: 1.3;
        }
        .brand-text strong {
            display: block;
            font-size: 26px;
            font-weight: 700;
        }
        .user-box {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 18px 18px;
            background: rgba(255,255,255,0.04);
            border-radius: 10px;
            padding: 10px 12px;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #535d69;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .user-name {
            font-size: 17px;
            font-weight: 600;
        }
        .nav-section {
            margin-top: 18px;
            padding: 0 12px;
        }
        .nav-label {
            font-size: 12px;
            color: #a8b2bc;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 12px 12px 8px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 17px;
            color: #d9e1ea;
            text-decoration: none;
            margin: 6px 0;
        }
        .nav-item.active {
            background: #0d6d84;
            color: white;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
        }
        .nav-item svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #f4f4f4;
            border-bottom: 1px solid #d3d3d3;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 26px;
        }
        .crumbs {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #3d4957;
            font-size: 14px;
        }
        .crumbs a { color: #3d4957; text-decoration: none; }
        .content {
            padding: 32px 28px 40px;
        }
        h2 {
            margin: 0 0 24px;
            font-size: 48px;
            line-height: 1.1;
            font-weight: 700;
            color: #2a3a45;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }
        .stat-card {
            background: #f3f5f5;
            border: 1px solid #d5d9dc;
            border-radius: 10px;
            min-height: 170px;
            padding: 16px 18px 12px;
            position: relative;
            overflow: hidden;
        }
        .stat-card.blue { border-top: 4px solid #2ca9d6; }
        .stat-card.teal { border-top: 4px solid #52c1b2; }
        .stat-card.yellow { border-top: 4px solid #d8c35e; }
        .stat-card.red { border-top: 4px solid #df6d6d; }
        .stat-icon {
            position: absolute;
            right: 18px;
            top: 18px;
            width: 70px;
            height: 70px;
            opacity: 0.18;
        }
        .stat-card.blue .stat-icon { fill: #2ca9d6; }
        .stat-card.teal .stat-icon { fill: #52c1b2; }
        .stat-card.yellow .stat-icon { fill: #d8c35e; }
        .stat-card.red .stat-icon { fill: #df6d6d; }
        .stat-value {
            font-size: 58px;
            font-weight: 700;
            line-height: 1;
            margin-top: 18px;
            color: #2d3a40;
        }
        .stat-label {
            font-size: 20px;
            margin-top: 8px;
            color: #4a5865;
        }
        .stat-more {
            display: inline-block;
            margin-top: 16px;
            color: #0a6e86;
            text-decoration: none;
            border: 1px solid #1a8aa3;
            border-radius: 999px;
            padding: 7px 18px;
            font-size: 15px;
            background: rgba(255,255,255,0.2);
        }
        .lower-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
            margin-top: 12px;
        }
        .panel {
            background: #f3f5f5;
            border: 1px solid #d5d9dc;
            border-radius: 10px;
            overflow: hidden;
        }
        .panel-header {
            padding: 14px 18px;
            font-size: 24px;
            font-weight: 700;
            color: #0d6d84;
            border-bottom: 1px solid #d5d9dc;
            background: rgba(255,255,255,0.15);
        }
        .stock-list {
            padding: 0 18px;
        }
        .stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            padding: 18px 0;
            border-bottom: 1px solid #dfe5e8;
            color: #2d3a40;
        }
        .stock-item:last-child { border-bottom: none; }
        .stock-badge {
            color: #d74848;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .stock-badge::before {
            content: "▲";
            font-size: 12px;
        }
        .income-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 230px;
            padding: 18px 16px;
        }
        .income-label {
            font-size: 18px;
            letter-spacing: 0.05em;
            color: #2d3a40;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .income-value {
            color: #0d6d84;
            font-size: 54px;
            font-weight: 700;
            letter-spacing: -1.5px;
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24"><path d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm0 2v12h10V6H7zm2 2h6v2H9V8zm0 4h6v2H9v-2z"/></svg>
                </div>
                <div class="brand-text">
                    <strong>PharmaPOS</strong>
                    Admin Terminal
                </div>
            </div>

            <div class="user-box">
                <div class="avatar">A</div>
                <div class="user-name">{{ $user['name'] ?? 'admin' }}</div>
            </div>

            <nav class="nav-section">
                <div class="nav-item active">
                    <svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-8.5z"/></svg>
                    Dashboard
                </div>
                <div class="nav-label">Master</div>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M16 11V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6H4v10h16V11h-4zm-2-6h-4V5h4v6h-4V5zm-8 8h4v6H6v-6zm12 0v6h-4v-6h4z"/></svg> Supplier</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M16 11a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-8 7c0-2.5 2.5-4 6-4s6 1.5 6 4v1H8v-1z"/></svg> Customer</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7zm2 0 6 4 6-4H6z"/></svg> Barang</a>
                <div class="nav-label">Transaksi</div>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M4 5h16v3H4V5zm1 5h14l1 9H4l1-9zm5 2v5h2v-5H10zm4 0v5h2v-5h-2z"/></svg> Pembelian</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h6v2H7v-2zM4 4h16v16H4V4z"/></svg> Penjualan</a>
                <div class="nav-label">Report</div>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2h12a2 2 0 0 1 2 2v16l-4-3-4 3-4-3-4 3V4a2 2 0 0 1 2-2zm2 4h8v2H8V6zm0 4h8v2H8v-2z"/></svg> Laporan Pembelian</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2h12a2 2 0 0 1 2 2v16l-4-3-4 3-4-3-4 3V4a2 2 0 0 1 2-2zm2 4h8v2H8V6zm0 4h8v2H8v-2z"/></svg> Laporan Penjualan</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M4 20h16v2H4zm2-2h12V8H6v10zm2-8h8v2H8V10zm0 4h8v2H8v-2z"/></svg> Laporan Stok</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 1-10 10A10 10 0 0 1 12 2zm1 5h-2v6h6v-2h-4z"/></svg> Pengaturan</a>
                <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg> User</a>
                <a href="{{ route('logout') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M10 17v2a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-7a2 2 0 0 0-2 2v2h2V5h7v14h-7v-2h-2zm-2.707-3.293 1.414 1.414L6.414 19H10v2H2v-8h2v3.586l4.293-4.293 1.414 1.414L7.414 12l.879-.879z"/></svg> Logout</a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="crumbs">
                    <a href="#">Home</a>
                    <span>/</span>
                    <span>Dashboard</span>
                    <span> | </span>
                    <span>{{ strtoupper($user['role'] ?? 'admin') }}</span>
                </div>
            </header>

            <div class="content">
                <h2>Dashboard</h2>

                <section class="stats-grid">
                    @foreach($stats as $stat)
                        <div class="stat-card {{ $stat['color'] }}">
                            <svg class="stat-icon" viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                            <div class="stat-value">{{ $stat['value'] }}</div>
                            <div class="stat-label">{{ $stat['label'] }}</div>
                            <a href="#" class="stat-more">More info</a>
                        </div>
                    @endforeach
                </section>

                <section class="lower-grid">
                    <div class="panel">
                        <div class="panel-header">Info Stok Barang</div>
                        <div class="stock-list">
                            @foreach($stockAlerts as $item)
                                <div class="stock-item">
                                    <span>{{ $item }}</span>
                                    <span class="stock-badge">Stok Kurang</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel income-box">
                        <div class="income-label">Total Hari Ini</div>
                        <div class="income-value">{{ $dailyIncome }}</div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
