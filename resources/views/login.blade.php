<!DOCTYPE html> <!-- HTML5 belgesi olduğunu belirtir -->
<html lang="tr"> <!-- Sayfanın dilini Türkçe olarak ayarlar -->
<head>
    <meta charset="UTF-8"> <!-- Türkçe karakter desteği sağlar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mobil cihazlarda responsive görünüm sağlar -->
    <title>Login</title> <!-- Tarayıcı sekmesinde görünen başlık -->

    <style> /* CSS kodlarının başladığı bölüm */
        body{
            margin: 0; /* Sayfanın varsayılan dış boşluğunu kaldırır */
            display: flex; /* Flexbox düzenini aktif eder */
            justify-content: center; /* İçeriği yatayda ortalar */
            align-items: center; /* İçeriği dikeyde ortalar */
            height: 100vh; /* Sayfa yüksekliğini ekranın tamamı yapar */
            background-color:#0f172a; /* Arka plan rengini koyu lacivert yapar */
            font-family: Arial, sans-serif; /* Yazı tipini Arial yapar */
        }

        .login-box{
            width: 350px; /* Giriş kutusunun genişliği */
            padding: 30px; /* İç boşluk bırakır */
            border-radius: 10px; /* Köşeleri yuvarlatır */
            background: #ffffff; /* Arka planı beyaz yapar */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Kutuya gölge ekler */
        }

        h2{
            text-align: center; /* Başlığı ortalar */
        }

        input{
            width: 100%; /* Input'un genişliğini kutunun tamamı yapar */
            padding: 10px; /* İç boşluk verir */
            margin-top: 5px; /* Üstten boşluk bırakır */
            box-sizing: border-box; /* Padding'in genişliği büyütmesini engeller */
            background: #ffffff; /* Arka plan rengini beyaz yapar */
        }

        button {
            width: 40%; /* Buton genişliğini %40 yapar */
            padding: 10px; /* İç boşluk verir */
            cursor: pointer; /* Üzerine gelince el işareti çıkar */
            background: #FAF9F7; /* Buton arka plan rengini belirler */

    
            /* Butonu sağa kaydıracak ayarlar */
            display: block;       /* Butonu blok eleman yapar */
            margin-left: auto;    /* Sol taraftaki boşluğu artırarak butonu sağa iter */
            margin-right: 0;      /* Sağ tarafta boşluk bırakmaz */
}
    </style> <!-- CSS kodlarının sonu -->

</head>
<body> <!-- Sayfanın görünen kısmı başlar -->

<div class="login-box"> <!-- Giriş kutusunu oluşturan div -->

    <h2>DNS Management System</h2> <!-- Sayfa başlığı -->

    <form method="POST" action="/login"> <!-- Form gönderildiğinde POST ile /login adresine gider -->
        @csrf <!-- Laravel CSRF güvenlik doğrulaması -->

        <div> <!-- E-mail alanını gruplar -->
            <label>E-mail</label><br> <!-- E-mail etiketi -->
            <input type="email" name="email" placeholder="E-mail"> <!-- E-mail giriş kutusu -->
        </div>

        <br> <!-- Satır boşluğu -->

        <div style="color:"> <!-- Şifre alanını gruplar (style şu an kullanılmıyor) -->
            <label>Şifre</label><br> <!-- Şifre etiketi -->
            <input type="password" name="password" placeholder="Şifreniz"> <!-- Şifre giriş kutusu -->
        </div>

        <br> <!-- Satır boşluğu -->

        <button type="submit">Giriş Yap</button> <!-- Forma basıldığında gönderme işlemini başlatır -->

    </form> <!-- Formun sonu -->

</div> <!-- Login kutusunun sonu -->

</body> <!-- Sayfanın görünen kısmı biter -->
</html> <!-- HTML belgesi sona erer -->