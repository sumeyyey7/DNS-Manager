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

/* Modal */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,.45);
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
.modal-footer{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:10px;
}

.btn-kaydet{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    font-weight:500;
}

.btn-kaydet:hover{
    background:#1d4ed8;
}

.btn-iptal{
    background:#e2e8f0;
    color:#334155;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    font-weight:500;
}

.btn-iptal:hover{
    background:#cbd5e1;
}
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

.btn-iptal{
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

.btn-iptal:hover{
    background:#e2e8f0;
    transform:translateY(-1px);
}
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
                <td><a href="#" class="domain-link">{{ $domain->domain_name }}</a></td>
                <td></td> 
                <td></td> 
                <td>{{ $domain->created_at ? $domain->created_at->format('d.m.Y H:i') : '' }}</td>
                <td>{{ $domain->description }}</td>
                <td>
                    <div class="islem-butonlari" style="justify-content: flex-end; padding-right:15px;">

                       <i class="fa-regular fa-pen-to-square"
                       title="Düzenle"
                       onclick="duzenle({{ $domain->id }})">
                      </i>


                        <i class="fa-regular fa-trash-can"
                        title="Sil"
                        onclick="domainSil({{ $domain->id }})"></i>

                    <form id="sil-formu-{{ $domain->id }}"
                        action="/domains/{{ $domain->id }}"
                        method="POST"
                        style="display:none;">

                        @csrf
                        @method('DELETE')

    </form>

</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div id="modal" class="modal">

    <div class="modal-icerik">

        <h2 id="modalTitle">Yeni Domain Ekle</h2>

<form id="domainForm" action="/domains" method="POST">

    @csrf

    <label>Domain Adı</label>

    <input id="domain_name" type="text" name="domain_name">

    <label>Açıklama</label>

    <textarea id="description" name="description"></textarea>

            <br><br>

            
            <div class="modal-footer">

    <button type="button" class="btn-iptal" onclick="modalKapat()"></i> İptal
    </button>

    <button type="submit" class="btn-kaydet"> Kaydet</button>

</div>

        </form>

    </div>

</div>
    
</div>
<script>
    function domainSil(id) {
        if (confirm('Bu domaini silmek istediğinize emin misiniz?')) {
            document.getElementById('sil-formu-' + id).submit();
        }
    }
    function modalAc(){
    document.getElementById("modal").style.display="block";
}

function modalKapat(){
    document.getElementById("modal").style.display="none";


}

    function duzenle(id){

    fetch('/domains/' + id + '/edit')

    .then(response => response.json())

    .then(domain => {

        modalAc();

        document.getElementById("modalTitle").innerHTML = "Domain Düzenle";

        document.getElementById("domain_name").value = domain.domain_name;

        document.getElementById("description").value = domain.description;

        document.querySelector(".btn-kaydet").innerHTML = "Güncelle";

        let form = document.getElementById("domainForm");

        form.action = "/domains/" + id;

        if(document.getElementById("putMethod") == null){

            let input = document.createElement("input");

            input.type = "hidden";

            input.name = "_method";

            input.value = "PUT";

            input.id = "putMethod";

            form.appendChild(input);

        }

    });

}
</script>
@endsection
</body>
</html>