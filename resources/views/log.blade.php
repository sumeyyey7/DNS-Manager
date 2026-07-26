@extends('layouts.app')

@section('content')

<style>
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

.table-kapsayici{
    background:#fff;
    padding:25px;
    border-radius:12px;
    border:1px solid #e2e8f0;
}

.table{
    width:100%;
    border-collapse:collapse;
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

.badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:5px 10px;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
}

/* MEVCUT RENKLER */
.badge-primary{
    background:#dbeafe;
    color:#2563eb;
}

.badge-warning{
    background:#fef3c7;
    color:#ca8a04;
}

.badge-danger{
    background:#fee2e2;
    color:#dc2626;
}

.badge-secondary{
    background:#f1f5f9;
    color:#475569;
}

/* YENİ EKLENEN RENKLER */
.badge-success{
    background:#dcfce7;
    color:#16a34a;
}

.badge-info{
    background:#e0f2fe;
    color:#0284c7;
}

.badge-purple{
    background:#f3e8ff;
    color:#9333ea;
}

.badge-dark{
    background:#e2e8f0;
    color:#0f172a;
}

.domain-vurgu{
    font-weight:600;
}

.tarih-renk{
    color:#64748b;
}
</style>

<div class="sayfa-ust">
    <div class="başlık">
        <h1>System Logs</h1>
        <p>All activities performed on the panel</p>
    </div>
</div>

<div class="table-kapsayici">

    <table class="table">

        <thead>
            <tr>
                <th>Action</th>
                <th>Domain</th>
                <th>User</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

        @forelse($logs as $log)

            <tr>

                <td>

                    @php
                        $action = strtolower($log->action);
                    @endphp

                    {{-- EKLEME (Mavi) --}}
                    @if(str_contains($action, 'ekle') || str_contains($action, 'create') || str_contains($action, 'add'))

                        <span class="badge badge-primary">
                            <i class="fa-solid fa-plus"></i>
                            {{ $log->action }}
                        </span>

                    {{-- GÜNCELLEME (Sarı) --}}
                    @elseif(str_contains($action, 'güncelle') || str_contains($action, 'update') || str_contains($action, 'edit'))

                        <span class="badge badge-warning">
                            <i class="fa-regular fa-pen-to-square"></i>
                            {{ $log->action }}
                        </span>

                    {{-- SİLME / KALDIRMA (Kırmızı) --}}
                    @elseif(str_contains($action, 'sil') || str_contains($action, 'delete') || str_contains($action, 'remove'))

                        <span class="badge badge-danger">
                            <i class="fa-regular fa-trash-can"></i>
                            {{ $log->action }}
                        </span>

                    {{-- GİRİŞ / BAŞARILI İŞLEM (Yeşil) --}}
                    @elseif(str_contains($action, 'giriş') || str_contains($action, 'login') || str_contains($action, 'onay'))

                        <span class="badge badge-success">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            {{ $log->action }}
                        </span>

                    {{-- ÇIKIŞ / OTURUM KAPATMA (Siyah / Koyu Gri) --}}
                    @elseif(str_contains($action, 'çıkış') || str_contains($action, 'logout'))

                        <span class="badge badge-dark">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            {{ $log->action }}
                        </span>

                    {{-- SORGULAMA / NAT / ARAMA (Açık Mavi) --}}
                    @elseif(str_contains($action, 'sorgu') || str_contains($action, 'nat') || str_contains($action, 'search'))

                        <span class="badge badge-info">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            {{ $log->action }}
                        </span>

                    {{-- YETKİ / AYAR / SİSTEM (Mor) --}}
                    @elseif(str_contains($action, 'ayar') || str_contains($action, 'setting') || str_contains($action, 'yetki'))

                        <span class="badge badge-purple">
                            <i class="fa-solid fa-gear"></i>
                            {{ $log->action }}
                        </span>

                    {{-- DİĞER HER ŞEY (Gri) --}}
                    @else

                        <span class="badge badge-secondary">
                            <i class="fa-solid fa-circle-info"></i>
                            {{ $log->action }}
                        </span>

                    @endif

                </td>

                <td class="domain-vurgu">
                    {{ $log->domain_name ?? '-' }}
                </td>

                <td>
                    {{ $log->user ?? '-' }}
                </td>

                <td class="tarih-renk">
                    {{ $log->created_at?->format('d.m.Y H:i') ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4" style="text-align:center;padding:20px;color:#94a3b8;">
                    No log records found yet.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection