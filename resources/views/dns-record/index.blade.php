@extends('layouts.app')

@section('content')

<style>
    /* ÜST ALAN DÜZENİ */
    .sayfa-ust {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .başlık h1 {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .buton-grubu {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-geri {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: .2s;
    }

    .btn-geri:hover {
        background-color: #e2e8f0;
        transform: translateY(-1px);
    }

    .btn-ekle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: .2s;
    }

    .btn-ekle:hover {
        background-color: #1d4ed8;
    }

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
        font-weight: 600;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    .islem-butonlari {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .islem-butonlari i {
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: .2s;
    }

    .islem-butonlari .fa-pen-to-square:hover { 
        color: #2563eb; 
        background-color: #eff6ff; 
    }

    .islem-butonlari .fa-trash-can:hover { 
        color: #dc2626; 
        background-color: #fef2f2; 
    }

    /* MODAL ALTYAPISI */
    .modal, .sil-modal-arka-plan {
        display: none;
        position: fixed;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 100%;
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(4px);
        z-index: 1000;
    }

    .modal-icerik {
        width: 450px;
        background: #fff;
        margin: 60px auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0,0,0,.18);
        animation: modalAcilis 0.25s ease-out;
    }

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

    .modal-icerik h2 {
        color: #0f172a;
        font-size: 30px;
        margin-bottom: 25px;
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

    .form-grup { 
        margin-bottom: 20px; 
    }
    
    .form-grup label { 
        display: block; 
        margin-bottom: 6px; 
        color: #334155; 
        font-size: 14px; 
        font-weight: 600; 
    }
    
    .form-grup input, .form-grup select {
        width: 100%;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: .2s;
        background-color: #ffffff;
    }
    
    .form-grup input:focus, .form-grup select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
    }

    .modal-footer, .sil-modal-butonlar { 
        display: flex; 
        justify-content: flex-end; 
        gap: 10px; 
        margin-top: 10px; 
    }
    
    .sil-modal-butonlar { 
        justify-content: center; 
    }

    .btn-kaydet {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 11px 22px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }
    
    .btn-kaydet:hover { 
        background: #1d4ed8; 
        transform: translateY(-1px); 
    }

    .btn-iptal, .btn-sil-vazgec {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 11px 22px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }
    
    .btn-iptal:hover, .btn-sil-vazgec:hover { 
        background: #e2e8f0; 
        transform: translateY(-1px); 
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
    
    .btn-sil-onay:hover { 
        background-color: #b91c1c; 
        transform: translateY(-1px); 
    }

    .alert-danger {
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#b91c1c;
        padding:12px;
        border-radius:8px;
        margin-bottom:15px;
    }

    .alert-danger ul {
        margin:0;
        padding-left:20px;
    }
</style>

<div class="sayfa-ust">
    <div class="başlık">
        <h1>DNS Records </h1>
    </div>
    <div class="buton-grubu">
        <a href="/domains" class="btn-geri"><i class="fa-solid fa-arrow-left"></i> Back to Domains</a>
        <button class="btn-ekle" onclick="modalAc()"><i class="fa-solid fa-plus"></i> Add New Record</button>
    </div>
</div>

<div class="table-kapsayici">
    <table class="table">
        <thead>
            <tr>
                <th>Domain</th>
                <th>Type</th>
                <th>Host</th>
                <th>Value</th>
                <th>Internal IP</th>
                <th>External IP</th>
                <th>TTL</th>
                <!-- GERİ EKLENDİ: İşlem başlığı -->
                <th style="text-align: right; padding-right: 15px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td style="font-weight: 600; color: #2563eb;">{{ $record->domain->domain_name ?? '-' }}</td>
                <td style="font-weight: 600; color: #0f172a;">{{ $record->type }}</td>
                <td>{{ $record->host }}</td>
                <td>{{ $record->value ?? '-' }}</td>
                <td>{{ $record->internal_ip ?? '-' }}</td>
                <td>{{ $record->external_ip ?? '-' }}</td>
                <td>{{ $record->ttl }}</td>
                <td>
                    <!-- GERİ EKLENDİ: İşlem butonları ve gizli silme formu -->
                    <div class="islem-butonlari" style="padding-right: 15px;">
                        <i class="fa-regular fa-pen-to-square" title="Edit" onclick="kayitDuzenle({{ json_encode($record) }})"></i>
                        <i class="fa-regular fa-trash-can" title="Delete" onclick="silmeOnayiniAc({{ $record->id }})"></i>

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

<div id="dnsModal" class="modal">
    <div class="modal-icerik">
        <h2 id="modalBaslik">Add New Record</h2>

        <form id="dnsForm" action="/dns-records" method="POST" autocomplete="off">
            @csrf
            <div id="methodAlani"></div>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grup">
                <label>Domain</label>
                <select name="domain_id" id="form_domain_id" required>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>
                            {{ $domain->domain_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-grup">
                <label>Record Type</label>
                <select name="type" id="form_type" required>
                    <option value="A" {{ old('type')=='A'?'selected':'' }}>A</option>
                    <option value="AAAA" {{ old('type')=='AAAA'?'selected':'' }}>AAAA</option>
                    <option value="CNAME" {{ old('type')=='CNAME'?'selected':'' }}>CNAME</option>
                    <option value="MX" {{ old('type')=='MX'?'selected':'' }}>MX</option>
                    <option value="TXT" {{ old('type')=='TXT'?'selected':'' }}>TXT</option>
                    <option value="NS" {{ old('type')=='NS'?'selected':'' }}>NS</option>
                </select>
            </div>

            <div class="form-grup">
                <label>Host</label>
                <input type="text" name="host" id="form_host" value="{{ old('host') }}" placeholder="e.g. @ or www" autocomplete="off" required>
            </div>

            <div class="form-grup" id="valueGroup">
                <label>Value</label>
                <input type="text" name="value" id="form_value" value="{{ old('value') }}" placeholder="e.g. 192.168.1.10" autocomplete="off">
            </div>

            <div class="form-grup">
                <label>Internal IP</label>
                <input type="text" name="internal_ip" id="form_internal_ip" value="{{ old('internal_ip') }}" placeholder="192.168.1.10" autocomplete="off">
            </div>

            <div class="form-grup">
                <label>External IP</label>
                <input type="text" name="external_ip" id="form_external_ip" value="{{ old('external_ip') }}" placeholder="85.105.10.25" autocomplete="off">
            </div>

            <div class="form-grup">
                <label>TTL</label>
                <input type="number" name="ttl" id="form_ttl" value="{{ old('ttl', 3600) }}" autocomplete="off" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-iptal" onclick="modalKapat()">Cancel</button>
                <button type="submit" class="btn-kaydet" id="btnSubmit">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="silmeOnayModal" class="sil-modal-arka-plan">
    <div class="sil-modal-kutu">
        <div class="sil-modal-ikon"><i class="fa-solid fa-circle-exclamation"></i></div>
        <h2>Are you sure?</h2>
        <p>Are you sure you want to delete this DNS record? This action cannot be undone.</p>
        <div class="sil-modal-butonlar">
            <button class="btn-sil-vazgec" onclick="silmeOnayiniKapat()">Cancel</button>
            <button class="btn-sil-onay" id="kesinSilButonu">Yes, Delete</button>
        </div>
    </div>
</div>

<script>
    let silinecekKayitId = null;

    // --- EKLEME VE DÜZENLEME MODALI FONKSİYONLARI ---
    function modalAc() {
        document.getElementById('modalBaslik').innerText = "Add New Record";
        document.getElementById('dnsForm').action = "/dns-records";
        document.getElementById('methodAlani').innerHTML = "";
        document.getElementById('dnsForm').reset();
        document.getElementById('form_ttl').value = "3600";
        document.getElementById('btnSubmit').innerHTML = "Save";
        document.getElementById('dnsModal').style.display = 'block';
        updateForm();
    }

    function kayitDuzenle(record) {
        document.getElementById('modalBaslik').innerText = "Edit Record";
        document.getElementById('dnsForm').action = "/dns-records/" + record.id;
        document.getElementById('methodAlani').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('form_domain_id').value = record.domain_id;
        document.getElementById('form_type').value = record.type;
        document.getElementById('form_host').value = record.host;
        document.getElementById('form_value').value = record.value;
        document.getElementById('form_internal_ip').value = record.internal_ip ?? "";
        document.getElementById('form_external_ip').value = record.external_ip ?? "";
        document.getElementById('form_ttl').value = record.ttl;
        document.getElementById('btnSubmit').innerHTML = "Update";
        document.getElementById('dnsModal').style.display = 'block';
        updateForm();
    }

    function modalKapat() {
        document.getElementById('dnsModal').style.display = 'none';
    }

    // --- SİLME MODALI FONKSİYONLARI ---
    function silmeOnayiniAc(id) {
        silinecekKayitId = id;
        document.getElementById('silmeOnayModal').style.display = 'block';
    }

    function silmeOnayiniKapat() {
        document.getElementById('silmeOnayModal').style.display = 'none';
    }

    document.getElementById('kesinSilButonu').addEventListener('click', function() {
        if (silinecekKayitId) {
            document.getElementById('sil-formu-' + silinecekKayitId).submit();
        }
    });

    // Dış boşluğa tıklanınca modalları kapatma güvenliği
    window.onclick = function(event) {
        var dnsModal = document.getElementById('dnsModal');
        var silModal = document.getElementById('silmeOnayModal');
        if (event.target == dnsModal) modalKapat();
        if (event.target == silModal) silmeOnayiniKapat();
    }

    @if ($errors->any())
        window.onload = function () {
            document.getElementById('dnsModal').style.display = 'block';
        };
    @endif

    const typeSelect = document.getElementById('form_type');
    const valueInput = document.getElementById('form_value');
    const hostInput = document.getElementById('form_host');
    const internalIpInput = document.getElementById('form_internal_ip');
    const externalIpInput = document.getElementById('form_external_ip');
    const valueGroup = document.getElementById('valueGroup');
    typeSelect.addEventListener('change', updateForm);

    function updateForm() {
        if (typeSelect.value === 'A' || typeSelect.value === 'AAAA') {
            valueGroup.style.display = "none";
            valueInput.value = "";
        } else {
            valueGroup.style.display = "block";
        }

        switch (typeSelect.value) {
            case 'A':
                valueInput.placeholder = "";
                hostInput.placeholder = "www";
                internalIpInput.placeholder = "192.168.1.10";
                externalIpInput.placeholder = "85.105.10.25";
                break;

            case 'AAAA':
                valueInput.placeholder = "";
                hostInput.placeholder = "www";
                internalIpInput.placeholder = "2001:db8::10";
                externalIpInput.placeholder = "2001:db8::25";
                break;

            case 'CNAME':
                valueInput.placeholder = "server.example.com";
                hostInput.placeholder = "www";
                internalIpInput.placeholder = "-";
                externalIpInput.placeholder = "-";
                break;

            case 'MX':
                valueInput.placeholder = "mail.example.com";
                hostInput.placeholder = "@";
                internalIpInput.placeholder = "-";
                externalIpInput.placeholder = "-";
                break;

            case 'NS':
                valueInput.placeholder = "ns1.example.com";
                hostInput.placeholder = "@";
                internalIpInput.placeholder = "192.168.1.2";
                externalIpInput.placeholder = "85.105.10.2";
                break;

            case 'TXT':
                valueInput.placeholder = "v=spf1 ~all";
                hostInput.placeholder = "@";
                internalIpInput.placeholder = "-";
                externalIpInput.placeholder = "-";
                break;

            default:
                valueInput.placeholder = "";
                hostInput.placeholder = "";
                internalIpInput.placeholder = "";
                externalIpInput.placeholder = "";
        }
    }

    updateForm();

    internalIpInput.addEventListener("blur", function () {
        if (typeSelect.value !== "A" && typeSelect.value !== "AAAA") {
            return;
        }

        fetch("/nat-search", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            },
            body: JSON.stringify({
                internal_ip: internalIpInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.external_ip !== "") {
                externalIpInput.value = data.external_ip;
            }
        });
    });
</script>

@endsection