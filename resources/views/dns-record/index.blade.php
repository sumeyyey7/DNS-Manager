@extends('layouts.app')

@section('content')
<style>
    /* ÜST ALAN DÜZENİ */
    .sayfa-ust {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }

    .başlık h1 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .başlık p {
        color: #64748b;
        font-size: 14px;
    }

    .buton-grubu {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }

    .btn-geri {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-geri:hover { background-color: #f8fafc; }

    /* Yeni Kayıt Ekle Butonu (Modalı Açar) */
    .btn-ekle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-ekle:hover { background-color: #1d4ed8; }

    /* TABLO STİLLERİ */
    .table-kapsayici {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    .table th {
        color: #64748b;
        font-weight: 500;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }

    .islem-butonlari {
        display: flex;
        gap: 12px;
        color: #64748b;
    }

    .islem-butonlari i { cursor: pointer; padding: 4px; border-radius: 4px; }
    .islem-butonlari .fa-pen-to-square:hover { color: #2563eb; background-color: #eff6ff; }
    .islem-butonlari .fa-trash-can:hover { color: #dc2626; background-color: #fef2f2; }

    /* MODAL (EKRANIN ORTASINDAKİ FORM) STİLLERİ - FOTOĞRAFA UYGUN */
    .modal-arka-plan {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-kutu {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 20px; /* Fotoğraftaki gibi daha oval köşeler */
        width: 100%;
        max-width: 550px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        animation: acilisAnimasyonu 0.25s ease-out;
    }

    @keyframes acilisAnimasyonu {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-kutu h2 {
        font-size: 32px; /* Fotoğraftaki büyük başlık stili */
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 25px;
    }

    .form-grup {
        margin-bottom: 20px;
    }

    .form-grup label {
        display: block;
        font-size: 15px;
        font-weight: 700; /* Fotoğraftaki kalın etiketler */
        color: #1e293b;
        margin-bottom: 8px;
    }

    .form-grup input, .form-grup select {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-family: inherit;
        font-size: 15px;
        color: #1e293b;
        outline: none;
        background-color: #ffffff;
    }

    .form-grup input:focus, .form-grup select:focus {
        border-color: #2563eb;
    }

    /* Fotoğraftaki Buton Tasarımları */
    .modal-butonlar {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-iptal {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 15px;
    }
    .btn-iptal:hover { background-color: #e2e8f0; }

    .btn-kaydet {
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 15px;
    }
    .btn-kaydet:hover { background-color: #1d4ed8; }
</style>

<div class="sayfa-ust">
    <div class="başlık">   
        <h1>DNS Records - {{ $records->first()->domain->domain_name ?? 'Domain' }}</h1>
        <p>{{ $records->first()->domain->domain_name ?? 'Domain' }} domainine alt DNS kayıtları</p>
    </div>
        
    <div class="buton-grubu">
        <a href="/domains" class="btn-geri"><i class="fa-solid fa-arrow-left"></i> Domainlere Dön</a>
        <button class="btn-ekle" onclick="modalAc()"><i class="fa-solid fa-plus"></i> Yeni Kayıt Ekle</button>
    </div>
</div>

<div class="table-kapsayici">
    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Host</th>
                <th>Value</th>
                <th>TTL</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td style="font-weight: 600;">{{ $record->type }}</td>
                <td>{{ $record->host }}</td>
                <td style="color: #475569;">{{ $record->value }}</td>
                <td>{{ $record->ttl }}</td>
                <td>
                    <div class="islem-butonlari">
                        <i class="fa-regular fa-pen-to-square" title="Düzenle"></i>
                        <i class="fa-regular fa-trash-can" title="Sil"></i>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="dnsModal" class="modal-arka-plan">
    <div class="modal-kutu">
        
        <h2>Yeni Kayıt Ekle</h2>

        <form action="/dns-records" method="POST">
            @csrf
            
            <input type="hidden" name="domain_id" value="{{ $records->first()->domain_id ?? '' }}">

            <div class="form-grup">
                <label>Kayıt Türü (Type)</label>
                <select name="type" required>
                    <option value="A">A</option>
                    <option value="CNAME">CNAME</option>
                    <option value="MX">MX</option>
                    <option value="TXT">TXT</option>
                    <option value="NS">NS</option>
                </select>
            </div>

            <div class="form-grup">
                <label>Host</label>
                <input type="text" name="host" placeholder="Örn: @ veya www" required>
            </div>

            <div class="form-grup">
                <label>Değer (Value)</label>
                <input type="text" name="value" placeholder="Örn: 192.168.1.10" required>
            </div>

            <div class="form-grup">
                <label>TTL</label>
                <input type="number" name="ttl" value="3600" required>
            </div>

            <div class="modal-butonlar">
                <button type="button" class="btn-iptal" onclick="modalKapat()">İptal</button>
                <button type="submit" class="btn-kaydet">Kaydet</button>
            </div>
        </form>

    </div>
</div>

<script>
    function modalAc() {
        document.getElementById('dnsModal').style.display = 'flex';
    }

    function modalKapat() {
        document.getElementById('dnsModal').style.display = 'none';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('dnsModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection