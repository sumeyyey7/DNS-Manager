<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Girişi - DNS Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #2563eb; /* Projenin ana koyu arka planı */
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 40px;
            border-radius: 16px; /* Diğer modallardaki gibi yumuşak oval köşeler */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .logo-alanı {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-alanı i {
            font-size: 36px;
            color: #2563eb; /* Projenin canlı mavi rengi */
            margin-bottom: 12px;
        }

        .logo-alanı h2 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .logo-alanı p {
            color: #64748b;
            font-size: 14px;
        }

        .form-grup {
            margin-bottom: 20px;
        }

        .form-grup label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        /* Giriş kutularını projedeki form tasarımlarıyla eşitledik */
        .form-grup input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-family: inherit;
            font-size: 15px;
            color: #1e293b;
            outline: none;
            background-color: #ffffff;
            transition: all 0.2s;
        }

        .form-grup input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* Giriş butonunu tam genişlikte ve projedeki mavi renkte ayarladık */
        .btn-giris {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 14px 22px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-giris:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-giris:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="login-box">

    <div class="logo-alanı">
        <i class="fa-solid fa-server"></i>
        <h2>DNS Management</h2>
        <p>Lütfen hesabınızla giriş yapın</p>
    </div>

    <form method="POST" action="/login">
        @csrf 

        <div class="form-grup">
            <label>E-mail Adresi</label>
            <input type="email" name="email" placeholder="email" required autocomplete="email" autofocus>
        </div>

        <div class="form-grup">
            <label>Şifre</label>
            <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn-giris">
            Giriş Yap <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>

    </form>

</div>

</body>
</html>