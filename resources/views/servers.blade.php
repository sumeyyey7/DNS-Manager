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

/* Yeni Sunucu Butonu */
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

.server-link{
    color:#2563eb;
    text-decoration:none;
    font-weight:500;
}

.server-link:hover{
    text-decoration:underline;
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

/* SİLME MODALI CSS */
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
.modal-icerik select,
.modal-icerik textarea{
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    font-size:14px;
    outline:none;
    transition:.2s;
    background-color: #fff;
}

.modal-icerik input:focus,
.modal-icerik select:focus,
.modal-icerik textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
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

@if ($errors->any())
<div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:15px;border-radius:8px;margin-bottom:20px;">
    <ul style="margin:0;padding-left:20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="sayfa-ust">
    <div class="başlık">   
        <h1>Servers</h1>
        <p>Manage all your DNS servers</p>
    </div>
        
    <div class="ekleme">  
        <button type="button" class="btn-ekle" onclick="modalAc()">
            <i class="fa-solid fa-plus"></i> Add New Server
        </button>
    </div>
</div>

<div class="table-kapsayici">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Server Name</th>
                <th>Type</th>
                <th>IP Address</th>
                <th>Username</th>
                <th>Status</th>
                <th style="text-align: right; padding-right: 15px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servers as $server)
            <tr>
                <td>{{ $server->id }}</td>
                <td><a href="/servers/{{ $server->id }}" class="server-link">{{ $server->name }}</a></td>
                <td>@if($server->type == 'internal')Internal @else External @endif</td>
                <td style="font-weight: 500;">{{ $server->ip }}</td>
                <td>{{ $server->username }}</td>
                <td>
                    @if($server->type == 'internal')
                        <span class="badge bg-primary">Local</span>
                    @elseif(isset($statuses[$server->id]) && $statuses[$server->id])
                        <span class="badge bg-success">Online</span>
                    @else
                        <span class="badge bg-danger">Offline</span>
                    @endif
                </td>
                <td>
                    <div class="islem-butonlari" style="justify-content: flex-end; padding-right:15px;">
                       <i class="fa-regular fa-pen-to-square" title="Edit" onclick="duzenle({{ $server->id }})"></i>
                       <i class="fa-regular fa-trash-can" title="Delete" onclick="silmeOnayiniAc({{ $server->id }})"></i>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- SUNUCU EKLE/DÜZENLE MODAL -->
<div id="modal" class="modal">
    <div class="modal-icerik">
        <h2 id="modalTitle">Add New Server</h2>
        
        <form id="serverForm" action="/servers" method="POST" autocomplete="off" novalidate>
            @csrf
            <div id="methodAlani"></div>

            <label>Server Name</label>
            <input id="name" type="text" name="name" required>

            <label>Server Type</label>
            <select id="type" name="type" required>
                <option value="internal">Internal</option>
                <option value="external">External</option>
            </select>

            <label>IP Address</label>
            <input id="ip_address" type="text" name="ip_address" placeholder="192.168.56.101" spellcheck="false" autocorrect="off" autocapitalize="off" required>

            <label>SSH Username</label>
            <input id="username" type="text" name="username" autocomplete="new-password" spellcheck="false" autocorrect="off" autocapitalize="off" required>

            <label>SSH Password</label>
            <input id="password" type="password" name="password" autocomplete="new-password">

            <div class="modal-footer">
                <button type="button" class="btn-iptal" onclick="modalKapat()">Cancel</button>
                <button type="submit" class="btn-kaydet">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- SİLME ONAY MODAL VE TEK SİLME FORMU -->
<div id="silmeOnayModal" class="sil-modal-arka-plan">
    <div class="sil-modal-kutu">
        <div class="sil-modal-ikon"><i class="fa-solid fa-circle-exclamation"></i></div>
        <h2>Are you sure?</h2>
        <p>Are you sure you want to delete this server? This action cannot be undone.</p>
        
        <form id="tekSilmeFormu" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="sil-modal-butonlar">
                <button type="button" class="btn-sil-vazgec" onclick="silmeOnayiniKapat()">Cancel</button>
                <button type="submit" class="btn-sil-onay">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- SİLME MODAL FONKSİYONLARI ---
    function silmeOnayiniAc(id) {
        document.getElementById('tekSilmeFormu').action = '/servers/' + id;
        document.getElementById('silmeOnayModal').style.display = 'block';
    }

    function silmeOnayiniKapat() {
        document.getElementById('silmeOnayModal').style.display = 'none';
    }

    // --- EKLEME/DÜZENLEME MODAL FONKSİYONLARI ---
    function modalAc(){
        document.getElementById("modalTitle").innerHTML = "Add New Server";
        document.getElementById("serverForm").action = "/servers";
        document.getElementById("methodAlani").innerHTML = "";
        document.getElementById("serverForm").reset();
        
        var passInput = document.getElementById("password");
        passInput.required = true;
        passInput.placeholder = "";

        document.querySelector(".btn-kaydet").innerHTML = "Save";
        document.getElementById("modal").style.display="block";
    }

    function modalKapat(){
        document.getElementById("modal").style.display="none";
    }

    function duzenle(id){
        // Bekleme durumunu engellemek için 4 saniyelik zaman aşımı (Timeout) eklendi
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 4000);

        fetch('/servers/' + id + '/edit', { signal: controller.signal })
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) throw new Error("Sunucu yanıt vermedi");
            return response.json();
        })
        .then(server => {
            document.getElementById("modalTitle").innerHTML = "Edit Server";
            document.getElementById("name").value = server.name;
            document.getElementById("type").value = server.type;
            document.getElementById("ip_address").value = server.ip;
            document.getElementById("username").value = server.username;

            var passInput = document.getElementById("password");
            passInput.value = "";
            passInput.required = false;
            passInput.placeholder = "Değiştirmek istemiyorsanız boş bırakın";
            
            document.querySelector(".btn-kaydet").innerHTML = "Update";

            let form = document.getElementById("serverForm");
            form.action = "/servers/" + id;
            document.getElementById("methodAlani").innerHTML = '<input type="hidden" name="_method" value="PUT" id="putMethod">';
            document.getElementById("modal").style.display="block";
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error("Düzenleme verisi alınamadı:", error);
            alert("Sunucu yanıt vermedi veya bağlantı zaman aşımına uğradı!");
        });
    }

    // Dış alana tıklayınca modalları kapatma
    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        var silModal = document.getElementById('silmeOnayModal');
        if (event.target == modal) modalKapat();
        if (event.target == silModal) silmeOnayiniKapat();
    }

    @if ($errors->any())
        window.onload = function () {
            document.getElementById("modal").style.display = "block";
        };
    @endif
</script>
@endsection