<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DNS Management System - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            display: flex; 
        }

        
        .DNS-box {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff; 
            border-right: 2px solid #e2e8f0;
            padding-top: 25px; 
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            padding-bottom: 30px;
        }

        
        .DNS-box .Dns-system h2 {
            font-size: 18px;
            color: #133659;
            padding-left: 20px;   
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        
        .katagori {
            list-style: none;
            padding-left: 15px;
            padding-right: 15px;
            flex-grow: 1; /* Listeyi yukarıda tutar */
        }

        
        .katagori li {
            margin-bottom: 8px; 
        }

       
        .katagori li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #64748b; 
            text-decoration: none; 
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        
        .katagori li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        
        .katagori li a:hover {
            background-color: #f1f5f9;
            color: #133659;
        }

        

        
        .cikis-yap {
            padding-left: 15px;
            padding-right: 15px;
        }

        .cikis-yap a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #dc2626; 
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            border-radius: 6px;
        }

        .cikis-yap a i {
            margin-right: 12px;
        }

        .cikis-yap a:hover {
            background-color: #fef2f2;
        }


        
        .ana-icerik {
            margin-left: 250px; 
            width: calc(100% - 250px); 
            padding: 40px;
        }

        
        .sayfa-baslik {
            margin-bottom: 30px;
        }

        .sayfa-baslik h1 {
            font-size: 28px;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .sayfa-baslik p {
            color: #64748b;
            font-size: 14px;
        }

        
        .kartlar-kapsayici {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .kart {
            background-color: #ffffff;
            flex: 1;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kart-bilgi h3 {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .kart-bilgi .sayi {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .kart-bilgi .alt-yazi {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Yuvarlak İkon Alanı */
        .kart-ikon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        
        .kart:nth-child(2) .kart-ikon { background-color: #f3e8ff; color: #a855f7; }
        .kart:nth-child(2) .sayi { color: #a855f7; }

        .kart:nth-child(3) .kart-ikon { background-color: #dcfce7; color: #22c55e; }
        .kart:nth-child(3) .sayi { color: #22c55e; }

        .kart:nth-child(4) .kart-ikon { background-color: #fef3c7; color: #f59e0b; }
        .kart:nth-child(4) .sayi { color: #f59e0b; }


        
        .alt-bloklar {
            display: flex;
            gap: 20px;
        }

        .blok {
            background-color: #ffffff;
            flex: 1;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .blok h2 {
            font-size: 16px;
            color: #0f172a;
            margin-bottom: 20px;
        }

       
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        th {
            color: #94a3b8;
            font-weight: 500;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        td {
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        td a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="DNS-box">
        <div>
            <div class="Dns-system">
                <h2><i class="fa-solid fa-globe" style="color: #2563eb;"></i> DNS System</h2>
            </div>

            <ul class="katagori">
                <li class="active"><a href="http://127.0.0.1:8000/dashboard"><i class="fa-solid fa-house"></i> Anasayfa</a></li>
                <li><a href="/domains"><i class="fa-solid fa-globe"></i> Domainler</a></li>
                <li><a href="/dns-records"><i class="fa-solid fa-database"></i> DNS Kayıtları</a></li>
                <li><a href="/logs"><i class="fa-solid fa-file-lines"></i> Loglar</a></li>
                <li><a href="#"><i class="fa-solid fa-gear"></i> Ayarlar</a></li>
            </ul>
        </div>

        <div class="cikis-yap">
            <a href="/logout"><i class="fa-solid fa-right-from-bracket"></i>Çıkış</a>
        </div>
    </div>

    <div class="ana-icerik"> @yield('content')</div>

</body>
</html>