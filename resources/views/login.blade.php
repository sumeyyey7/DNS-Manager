<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>System Login - DNS Management System</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#e6edfb;
            font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;
        }

        .login-box{
            width:100%;
            max-width:420px;
            background:#fff;
            padding:40px;
            border-radius:16px;
            box-shadow:0 25px 50px -12px rgba(0,0,0,.25);
        }

        .logo-alanı{
            text-align:center;
            margin-bottom:30px;
        }

        .logo-alanı i{
            font-size:36px;
            color:#2563eb;
            margin-bottom:12px;
        }

        .logo-alanı h2{
            font-size:24px;
            font-weight:800;
            color:#0f172a;
            margin-bottom:6px;
        }

        .logo-alanı p{
            color:#64748b;
            font-size:14px;
        }

        .form-grup{
            margin-bottom:20px;
        }

        .form-grup label{
            display:block;
            margin-bottom:8px;
            font-size:14px;
            font-weight:700;
            color:#1e293b;
        }

        .form-grup input{
            width:100%;
            padding:14px 16px;
            border:1px solid #cbd5e1;
            border-radius:10px;
            outline:none;
            font-size:15px;
            font-family:inherit;
            transition:.2s;
        }

        .form-grup input:focus{
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,.15);
        }

        .btn-giris{
            width:100%;
            display:flex;
            justify-content:center;
            align-items:center;
            gap:8px;
            margin-top:10px;
            padding:14px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:#fff;
            cursor:pointer;
            font-size:15px;
            font-weight:600;
            transition:.2s;
        }

        .btn-giris:hover{
            background:#1d4ed8;
            transform:translateY(-1px);
        }

        .btn-giris:active{
            transform:translateY(0);
        }

        .alert{
            background:#fee2e2;
            color:#b91c1c;
            border:1px solid #fecaca;
            border-radius:8px;
            padding:12px;
            margin-bottom:18px;
            font-size:14px;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="logo-alanı">
        <i class="fa-solid fa-server"></i>
        <h2>DNS Management</h2>
        <p>Please log in with your account</p>
    </div>

    @if(session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="form-grup">
            <label>Email Address</label>
            <input
        type="email"
        name="email"
        placeholder=""
        autocomplete="off"
        autofocus
    >
</div>

        <div class="form-grup">
            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder=""
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn-giris">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
            Log In
        </button>

    </form>

</div>

</body>
</html>