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

    /* MODAL STİLLERİ */
    .modal-arka-plan, .sil-modal-arka-plan {
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
        border-radius: 20px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        animation: acilisAnimasyonu 0.25s ease-out;
    }

    .sil-modal-kutu {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: acilisAnimasyonu 0.2s ease-out;
    }

    @keyframes acilisAnimasyonu {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-kutu h2 {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 25px;
    }

    .sil-modal-ikon { font-size: 48px; color: #dc2626; margin-bottom: 15px; }
    .sil-modal-kutu h2 { font-size: 20px; color: #0f172a; margin-bottom: 8px; }
    .sil-modal-kutu p { font-size: 14px; color: #64748b; margin-bottom: 25px; }

    .form-grup { margin-bottom: 20px; }
    .form-grup label { display: block; font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
    
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
    .form-grup input:focus, .form-grup select:focus { border-color: #2563eb; }

    .modal-butonlar, .sil-modal-butonlar { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .sil-modal-butonlar { justify-content: center; }

    .btn-iptal, .btn-sil-vazgec { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; }
    .btn-iptal:hover, .btn-sil-vazgec:hover { background-color: #e2e8f0; }

    .btn-kaydet { background-color: #2563eb; color: #ffffff; border: none; padding: 12px 28px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; }
    .btn-kaydet:hover { background-color: #1d4ed8; }

    .btn-sil-onay { background-color: #dc2626; color: #ffffff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; }
    .btn-sil-onay:hover { background-color: #b91c1c; }
</style>

<div class="sayfa-ust">
    <div class="başlık">   
        <h1>DNS Records - {{ $records->first()->domain->domain_name ?? 'Domain' }}</h1>
        <p>{{ $records->first()->domain->domain_name ?? 'Domain' }} domainine ait DNS kayıtları</p>
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
                        <i class="fa-regular fa-pen-to-square" title="Düzenle" 
                           onclick="kayitDuzenle({{ json_encode($record) }})"></i>
                        
                        <i class="fa-regular fa-trash-can" title="Sil" 
                           onclick="silmeOnayiniAc({{ $record->id }})"></i>

                        <form id="sil-formu-{{ $record->id }}" action="/dns-records/{{ $record->id }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="dnsModal" class="modal-arka-plan">
    <div class="modal-kutu">
        <h2 id="modalBaslik">Yeni Kayıt Ekle</h2>

        <form id="dnsForm" action="/dns-records" method="POST">
            @csrf
            <div id="methodAlani"></div>
            
            <div class="form-grup">
                <label>Domain</label>
                <select name="domain_id" id="form_domain_id" required>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}">
                            {{ $domain->domain_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-grup">
                <label>Kayıt Türü (Type)</label>
                <select name="type" id="form_type" required>
                    <option value="A">A</option>
                    <option value="CNAME">CNAME</option>
                    <option value="MX">MX</option>
                    <option value="TXT">TXT</option>
                    <option value="NS">NS</option>
                </select>
            </div>

            <div class="form-grup">
                <label>Host</label>
                <input type="text" name="host" id="form_host" placeholder="Örn: @ veya www" required>
            </div>

            <div class="form-grup">
                <label>Değer (Value)</label>
                <input type="text" name="value" id="form_value" placeholder="Örn: 192.168.1.10" required>
            </div>

            <div class="form-grup">
                <label>TTL</label>
                <input type="number" name="ttl" id="form_ttl" value="3600" required>
            </div>

            <div class="modal-butonlar">
                <button type="button" class="btn-iptal" onclick="modalKapat()">İptal</button>
                <button type="submit" class="btn-kaydet" id="btnSubmit">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<div id="silmeOnayModal" class="sil-modal-arka-plan">
    <div class="sil-modal-kutu">
        <div class="sil-modal-ikon"><i class="fa-solid fa-circle-exclamation"></i></div>
        <h2>Emin misiniz?</h2>
        <p>Bu DNS kaydını silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
        <div class="sil-modal-butonlar">
            <button class="btn-sil-vazgec" onclick="silmeOnayiniKapat()">Vazgeç</button>
            <button class="btn-sil-onay" id="kesinSilButonu">Evet, Sil</button>
        </div>
    </div>
</div>

<script>
    let silinecekKayitId = null;

    // --- EKLEME VE DÜZENLEME MODALI FONKSİYONLARI ---
    function modalAc() {
        // Formu temizle ve "Ekleme" moduna getir
        document.getElementById('modalBaslik').innerText = "Yeni Kayıt Ekle";
        document.getElementById('dnsForm').action = "/dns-records";
        document.getElementById('methodAlani').innerHTML = ""; // PUT methodunu kaldır
        document.getElementById('dnsForm').reset();
        document.getElementById('form_ttl').value = "3600";
        document.getElementById('dnsModal').style.display = 'flex';
    }

    function kayitDuzenle(record) {
        // Formu "Güncelleme" moduna getir
        document.getElementById('modalBaslik').innerText = "Kaydı Düzenle";
        document.getElementById('dnsForm').action = "/dns-records/" + record.id;
        document.getElementById('methodAlani').innerHTML = '@method("PUT")'; // Laravel Güncelleme şartı
        
        // Verileri inputlara doldur
        document.getElementById('form_domain_id').value = record.domain_id;
        document.getElementById('form_type').value = record.type;
        document.getElementById('form_host').value = record.host;
        document.getElementById('form_value').value = record.value;
        document.getElementById('form_ttl').value = record.ttl;
        
        document.getElementById('dnsModal').style.display = 'flex';
    }

    function modalKapat() {
        document.getElementById('dnsModal').style.display = 'none';
    }

    // --- SİLME MODALI FONKSİYONLARI ---
    function silmeOnayiniAc(id) {
        silinecekKayitId = id;
        document.getElementById('silmeOnayModal').style.display = 'flex';
    }

    function silmeOnayiniKapat() {
        document.getElementById('silmeOnayModal').style.display = 'none';
    }

    document.getElementById('kesinSilButonu').addEventListener('click', function() {
        if (silinecekKayitId) {
            document.getElementById('sil-formu-' + silinecekKayitId).submit();
        }
    });

    // Boşluğa tıklayınca modalları kapat
    window.onclick = function(event) {
        var dnsModal = document.getElementById('dnsModal');
        var silModal = document.getElementById('silmeOnayModal');
        if (event.target == dnsModal) modalKapat();
        if (event.target == silModal) silmeOnayiniKapat();
    }
</script>
@endsection