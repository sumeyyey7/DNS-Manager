# DNS Manager

DNS Manager, Laravel ve BIND9 kullanılarak geliştirilmiş web tabanlı bir DNS yönetim panelidir. Bu proje sayesinde kullanıcılar, alan adlarını (domain) ve DNS kayıtlarını kullanıcı dostu bir arayüz üzerinden yönetebilir, oluşturulan zone dosyalarını doğrulayabilir ve değişiklikleri hem yerel hem de uzak DNS sunucularına uygulayabilir.

## Proje Amacı

Bu projenin amacı, BIND9 üzerinde manuel olarak gerçekleştirilen DNS yönetim işlemlerini web tabanlı bir arayüze taşıyarak yönetimi kolaylaştırmak ve birden fazla DNS sunucusunun merkezi olarak yönetilebilmesini sağlamaktır.

## Özellikler

- Kullanıcı giriş sistemi
- Domain oluşturma, güncelleme ve silme
- DNS kayıtlarını (A, AAAA, CNAME, MX, NS, TXT, PTR vb.) yönetme
- Zone dosyalarının otomatik oluşturulması
- Zone dosyalarının doğrulanması
- BIND9 servisinin yeniden yüklenmesi
- İşlem kayıtlarının (Log) tutulması
- Internal ve External DNS sunucu desteği
- SSH ile uzak DNS sunucularına bağlantı
- Zone dosyalarının uzak sunuculara aktarılması
- Çoklu DNS sunucu yönetimi
- Sunucu bağlantı durumu kontrolü

## Kullanılan Teknolojiler

- Laravel
- PHP
- MySQL
- BIND9
- SSH
- Bootstrap
- Ubuntu Linux

## Proje Yapısı

```
app/
├── Http/
├── Models/
├── Services/
│   ├── BindService.php
│   └── ExternalBindService.php
├── database/
├── public/
├── resources/
├── routes/
└── storage/
```

## Çalışma Mantığı

1. Kullanıcı sisteme giriş yapar.
2. Domain ekler veya mevcut domainleri yönetir.
3. DNS kayıtlarını oluşturur veya günceller.
4. Sistem ilgili zone dosyalarını otomatik olarak oluşturur.
5. Yapılandırma dosyaları doğrulanır.
6. BIND9 servisi yeniden yüklenir.
7. İstenirse değişiklikler SSH üzerinden uzak DNS sunucularına gönderilir.

## Kurulum

Projeyi klonlayın.

```bash
git clone https://github.com/sumeyyey7/dns-manager.git
```

Proje dizinine girin.

```bash
cd dns-manager
```

Gerekli bağımlılıkları yükleyin.

```bash
composer install
npm install
```

Ortam dosyasını oluşturun.

```bash
cp .env.example .env
```

Uygulama anahtarını oluşturun.

```bash
php artisan key:generate
```

Veritabanı ayarlarını yaptıktan sonra migrationları çalıştırın.

```bash
php artisan migrate
```

Frontend dosyalarını derleyin.

```bash
npm run dev
```

Projeyi başlatın.

```bash
php artisan serve
```

## Kullanılan BIND Komutları

```bash
named-checkconf
named-checkzone
rndc reload
```


## Geliştirici

**Sümeyye Yeşilyurt**

Bilgisayar Mühendisliği Öğrencisi

---

Bu proje, üniversite staj projesi kapsamında geliştirilmiştir.
