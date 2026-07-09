<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        
        .DNS-box {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8fafc; 
            border-right: 2px solid #334155;
            padding-top: 25px; 
        }

       
        .DNS-box .Dns-system h2 {
            font-family: 'Segoe UI', sans-serif;
            font-size: 18px;
            color: #133659;
            padding-left: 50px;   
             
            margin: 0;
        }

        .katagori{
            list-style: none;
            padding-top: 60px;
            padding-left: 50px;
        }

        .katagori li {
            margin-bottom: 8px; 
        }


        .katagori li a {
            display: block;
            padding: 10px 15px;
            color: #446490; /* Pasif gri renk */
            font-family: 'Segoe UI', sans-serif;
            text-decoration: none; /* Alt çizgiyi kaldırdık */
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <div class="DNS-box">
        <div class="Dns-system">
            <h2>DNS Management System</h2>
        </div>

        <ul class="katagori">
        <li class="active"><a href="#">Anasayfa</a></li>
        <li><a href="#">Domainler</a></li>
        <li><a href="#">DNS Kayıtları</a></li>
        <li><a href="#">Loglar</a></li>
        <li><a href="#">Kullanıcılar</a></li>
        <li><a href="#">Ayarlar</a></li>

    </ul>
    </div>
    
</body>
</html>