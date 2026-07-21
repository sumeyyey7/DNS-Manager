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

                    @if(str_contains($action,'ekle'))

                        <span class="badge badge-primary">
                            <i class="fa-solid fa-plus"></i>
                            {{ $log->action }}
                        </span>

                    @elseif(str_contains($action,'güncelle'))

                        <span class="badge badge-warning">
                            <i class="fa-regular fa-pen-to-square"></i>
                            {{ $log->action }}
                        </span>

                    @elseif(str_contains($action,'sil'))

                        <span class="badge badge-danger">
                            <i class="fa-regular fa-trash-can"></i>
                            {{ $log->action }}
                        </span>

                    @else

                        <span class="badge badge-secondary">
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