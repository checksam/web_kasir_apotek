<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaPOS</title>
    <style>
        body {
            margin: 0;
            background: #ececec;
            font-family: Arial, sans-serif;
        }
        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .login-box {
            width: 100%;
            max-width: 560px;
            text-align: center;
        }
        .logo-box {
            width: 82px;
            height: 82px;
            background: #0d6d84;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 8px 18px rgba(13, 109, 132, 0.2);
        }
        .logo-box svg {
            width: 42px;
            height: 42px;
            fill: #ffffff;
        }
        h1 {
            margin: 0;
            font-size: 52px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: -1.5px;
        }
        .subtitle {
            margin-top: 8px;
            font-size: 18px;
            color: #5b6472;
            font-weight: 400;
        }
        .panel {
            background: rgba(255,255,255,0.15);
            border: 1px solid #cfcfcf;
            border-radius: 10px;
            margin-top: 36px;
            padding: 28px 26px 20px;
            text-align: left;
        }
        .field {
            margin-bottom: 20px;
        }
        .label {
            display: block;
            font-size: 17px;
            color: #44515d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid #c2c8d0;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            overflow: hidden;
        }
        .input-wrap svg {
            width: 22px;
            height: 22px;
            fill: #6d7b8a;
            margin-left: 14px;
            flex-shrink: 0;
        }
        .input-wrap input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 18px;
            color: #2f3a46;
            padding: 15px 14px 15px 12px;
        }
        .input-wrap input::placeholder {
            color: #8a929e;
        }
        .meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 16px;
            color: #4f5d6d;
            gap: 12px;
        }
        .check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .check input {
            width: 18px;
            height: 18px;
            accent-color: #0d6d84;
        }
        .forgot {
            color: #0d6d84;
            text-decoration: none;
            font-weight: 500;
        }
        .submit-btn {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 18px 22px;
            background: linear-gradient(180deg, #0c738a 0%, #0b5d75 100%);
            color: white;
            font-size: 30px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 18px rgba(12, 115, 138, 0.22);
        }
        .submit-btn svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }
        .footer-note {
            margin-top: 26px;
            text-align: center;
            color: #606d7a;
            font-size: 18px;
        }
        @media (max-width: 640px) {
            h1 {
                font-size: 38px;
            }
            .panel {
                padding: 24px 18px 18px;
            }
            .submit-btn {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-box">
            <div class="logo-box" aria-label="Logo PharmaPOS">
                <svg viewBox="0 0 24 24"><path d="M19 7h-1V6a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1h-1V6a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v1H6a2 2 0 0 0-2 2v8a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V9a2 2 0 0 0-2-2Zm-6 3a1 1 0 0 1 2 0v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2h-2a1 1 0 1 1 0-2h2v-2Zm-7-1h2v1H6V9Zm12 0h2v1h-2V9Z"/></svg>
            </div>

            <h1>PharmaPOS</h1>
            <div class="subtitle">Terminal Administrasi Farmasi</div>

            <form class="panel" method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="field">
                    <label class="label" for="email">Nama Pengguna</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan nama pengguna" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24"><path d="M17 8h-1V7a4 4 0 1 0-8 0v1H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2Zm-6 7.732V16a1 1 0 1 1-2 0v-.268A2 2 0 0 1 9 12a2 2 0 1 1 4 0 2 2 0 0 1-2 3.732ZM13 8H11V7a2 2 0 1 1 4 0v1h-2Z"/></svg>
                        <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required>
                    </div>
                </div>

                @if ($errors->any())
                    <div style="color:#b91c1c; font-size:14px; margin-bottom: 12px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="meta">
                    <label class="check"><input type="checkbox" name="remember"> <span>Ingat saya</span></label>
                    <a href="#" class="forgot">Lupa sandi?</a>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Masuk</span>
                    <svg viewBox="0 0 24 24"><path d="M5 12h11.586l-4.293 4.293 1.414 1.414L20.414 12l-6.707-6.707-1.414 1.414L16.586 11H5z"/></svg>
                </button>
            </form>

            <div class="footer-note">Hanya untuk penggunaan internal yang sah.<br>© 2026 PharmaPOS. Hak cipta dilindungi.</div>
        </div>
    </div>
</body>
</html>
