<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Domainler</title>
     @extends('layouts.app')

@section('content')
<style>
/* Üst Alan */
.sayfa-ust{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.başlık h1{
    font-size:28px;
    color:#0f172a;
    margin-bottom:5px;
}

.başlık p{
    color:#64748b;
    font-size:14px;
}

/* Yeni Domain Butonu */
.btn-ekle{
    display:flex;
    align-items:center;
    gap:8px;
    background:#2563eb;
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    font-weight:500;
    transition:.2s;
}

.btn-ekle:hover{
    background:#1d4ed8;
}

/* Tablo */
.table-kapsayici{
    background:#fff;
    padding:25px;
    border-radius:12px;
    border:1px solid #e2e8f0;
}

.table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

.table th{
    text-align:left;
    color:#64748b;
    font-weight:600;
    padding-bottom:15px;
    border-bottom:1px solid #e2e8f0;
}

.table td{
    padding:16px 0;
    border-bottom:1px solid #f1f5f9;
    color:#334155;
}

.domain-link{
    color:#2563eb;
    text-decoration:none;
    font-weight:500;
}

.badge-aktif{
    background:#dcfce7;
    color:#15803d;
    padding:4px 10px;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
}

.islem-butonlari{
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.islem-butonlari i{
    cursor:pointer;
    padding:8px;
    border-radius:6px;
    transition:.2s;
}

.islem-butonlari i:hover{
    background:#f1f5f9;
}
.islem-butonlari .fa-pen-to-square:hover { color: #2563eb; background-color: #eff6ff; }
.islem-butonlari .fa-trash-can:hover { color: #dc2626; background-color: #fef2f2; }

/* Modal Ortak Altyapı */
.modal, .sil-modal-arka-plan {
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,.45);
    backdrop-filter: blur(4px);
    z-index:1000;
}

/* Modal Kutusu */
.modal-icerik{
    width:450px;
    background:#fff;
    margin:90px auto;
    padding:30px;
    border-radius:12px;
    box-shadow:0 15px 40px rgba(0,0,0,.18);
    animation: modalAcilis 0.25s ease-out;
}

/* ŞIK SİLME MODALI ÖZEL CSS */
.sil-modal-kutu {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    width: 100%;
    max-width: 400px;
    margin: 150px auto;
    text-align: center;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    animation: modalAcilis 0.2s ease-out;
}

@keyframes modalAcilis {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.sil-modal-ikon {
    font-size: 48px;
    color: #dc2626;
    margin-bottom: 15px;
}

.sil-modal-kutu h2 {
    font-size: 20px;
    color: #0f172a;
    margin-bottom: 8px;
    font-weight: 700;
}

.sil-modal-kutu p {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 25px;
}

.modal-icerik h2{
    color:#0f172a;
    font-size:30px;
    margin-bottom:25px;
}

.modal-icerik label{
    display:block;
    margin-bottom:6px;
    color:#334155;
    font-size:14px;
    font-weight:600;
}

.modal-icerik input,
.modal-icerik textarea{
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    font-size:14px;
    outline:none;
    transition:.2s;
}

.modal-icerik input:focus,
.modal-icerik textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

.modal-icerik textarea{
    resize:none;
    height:90px;
}

/* Butonlar */
.modal-footer, .sil-modal-butonlar{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:10px;
}
.sil-modal-butonlar { justify-content: center; }

.btn-kaydet{
    display:flex;
    align-items:center;
    gap:8px;
    background:#2563eb;
    color:#fff;
    border:none;
    padding:11px 22px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    font-weight:600;
    transition:.2s;
}

.btn-kaydet:hover{
    background:#1d4ed8;
    transform:translateY(-1px);
}

.btn-iptal, .btn-sil-vazgec{
    display:flex;
    align-items:center;
    gap:8px;
    background:#f1f5f9;
    color:#475569;
    border:1px solid #cbd5e1;
    padding:11px 22px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    font-weight:600;
    transition:.2s;
}

.btn-iptal:hover, .btn-sil-vazgec:hover{
    background:#e2e8f0;
    transform:translateY(-1px);
}

.btn-sil-onay {
    background-color: #dc2626;
    color: #ffffff;
    border: none;
    padding: 11px 22px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: .2s;
}
.btn-sil-onay:hover { background-color: #b91c1c; transform:translateY(-1px); }
</style>

<div class="sayfa-ust">
    <div class="başlık">   
        <h1>Domains</h1>
        <p>Tüm domainlerinizi yönetin</p>
    </div>
        
    <div class="ekleme">  
        <button type="button" class="btn-ekle" onclick="modalAc()">
            <i class="fa-solid fa-plus"></i> Yeni Domain Ekle
        </button>
    </div>
</div>

<div class="table-kapsayici">
    <table class="table">
        <thead>
            <tr>
                <th>Domain</th>
                <th>Kayıt Sayısı</th>
                <th>Durum</th>
                <th>Eklenme Tarihi</th>
                <th>Açıklama</th>
                <th style="text-align: right; padding-right: 15px;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($domains as $domain)
            <tr>
                <td><a href="/domains/{{ $domain->id }}/dns-records" class="domain-link">{{ $domain->domain_name }}</a></td>
                <td style="font-weight: 600; padding-left: 15px;">{{ $domain->dns_records_count ?? 0 }}</td> 
                <td><span class="badge-aktif">Aktif</span></td> 
                <td>{{ $domain->created_at ? $domain->created_at->format('d.m.Y H:i') : '' }}</td>
                <td>{{ $domain->description }}</td>
                <td>
                    <div class="islem-butonlari" style="justify-content: flex-end; padding-right:15px;">
                       
                       <i class="fa-regular fa-pen-to-square" title="Düzenle" onclick="duzenle({{ $domain->id }})"></i>

                       <i class="fa-regular fa-trash-can" title="Sil" onclick="silmeOnayiniAc({{ $domain->id }})"></i>

                       <form id="sil-formu-{{ $domain->id }}" action="/domains/{{ $domain->id }}" method="POST" style="display:none;">
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

<div id="modal" class="modal">
    <div class="modal-icerik">
        <h2 id="modalTitle">Yeni Domain Ekle</h2>
        <form id="domainForm" action="/domains" method="POST">
            @csrf
            <div id="methodAlani"></div>

            <label>Domain Adı</label>
            <input id="domain_name" type="text" name="domain_name">

            <label>Açıklama</label>
            <textarea id="description" name="description"></textarea>
            
            <div class="modal-footer">
                <button type="button" class="btn-iptal" onclick="modalKapat()">İptal</button>
                <button type="submit" class="btn-kaydet">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<div id="silmeOnayModal" class="sil-modal-arka-plan">
    <div class="sil-modal-kutu">
        <div class="sil-modal-ikon"><i class="fa-solid fa-circle-exclamation"></i></div>
        <h2>Emin misiniz?</h2>
        <p>Bu domaini silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
        <div class="sil-modal-butonlar">
            <button class="btn-sil-vazgec" onclick="silmeOnayiniKapat()">Vazgeç</button>
            <button class="btn-sil-onay" id="kesinSilButonu">Evet, Sil</button>
        </div>
    </div>
</div>

<script>
    let silinecekDomainId = null;

    // --- SİLME MODAL FONKSİYONLARI ---
    function silmeOnayiniAc(id) {
        silinecekDomainId = id;
        document.getElementById('silmeOnayModal').style.display = 'block';
    }

    function silmeOnayiniKapat() {
        document.getElementById('silmeOnayModal').style.display = 'none';
    }

    document.getElementById('kesinSilButonu').addEventListener('click', function() {
        if (silinecekDomainId) {
            document.getElementById('sil-formu-' + silinecekDomainId).submit();
        }
    });

    // --- EKLEME/DÜZENLEME MODAL FONKSİYONLARI ---
    function modalAc(){
        document.getElementById("modalTitle").innerHTML = "Yeni Domain Ekle";
        document.getElementById("domainForm").action = "/domains";
        document.getElementById("methodAlani").innerHTML = "";
        document.getElementById("domainForm").reset();
        document.querySelector(".btn-kaydet").innerHTML = "Kaydet";
        document.getElementById("modal").style.display="block";
    }

    function modalKapat(){
        document.getElementById("modal").style.display="none";
    }

    function duzenle(id){
        fetch('/domains/' + id + '/edit')
        .then(response => response.json())
        .then(domain => {
            document.getElementById("modal").style.display="block";
            document.getElementById("modalTitle").innerHTML = "Domain Düzenle";
            document.getElementById("domain_name").value = domain.domain_name;
            document.getElementById("description").value = domain.description;
            document.querySelector(".btn-kaydet").innerHTML = "Güncelle";

            let form = document.getElementById("domainForm");
            form.action = "/domains/" + id;
            document.getElementById("methodAlani").innerHTML = '<input type="hidden" name="_method" value="PUT" id="putMethod">';
        });
    }

    // Boşluğa tıklayınca modalları kapatma güvenliği
    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        var silModal = document.getElementById('silmeOnayModal');
        if (event.target == modal) modalKapat();
        if (event.target == silModal) silmeOnayiniKapat();
    }
</script>
@endsection
</body>
</html>