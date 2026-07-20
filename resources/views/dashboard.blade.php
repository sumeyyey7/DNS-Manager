@extends('layouts.app')

@section('content')
<div class="sayfa-baslik">
    <h1>Dashboard</h1>
    <p>System overview and statistics</p>
</div>

<div class="kartlar-kapsayici">
    <div class="kart">
        <div class="kart-bilgi">
            <h3>Total Domains</h3>
            <div class="sayi">{{ $domainCount }}</div>
            <div class="alt-yazi">Number of active domains</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-globe"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Total DNS Records</h3>
            <div class="sayi">{{ $dnsRecordCount }}</div>
            <div class="alt-yazi">Total number of records</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-database"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Total Users</h3>
            <div class="sayi">{{ $userCount }}</div>
            <div class="alt-yazi">Number of registered users</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-users"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Last 24 Hours Activity</h3>
            <div class="sayi">{{ $islemSayisi ?? 0 }}</div>
            <div class="alt-yazi">Actions in the last 24 hours</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-chart-line"></i></div>
    </div>
</div>

<div class="alt-blocks" style="display: flex; gap: 20px;">

    <div class="blok" style="background-color: #ffffff; flex: 1; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 16px; color: #0f172a; margin-bottom: 20px;">Recently Added Domains</h2>
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th style="text-align: right; padding-right: 10px;">Record Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sonDomainler as $domain)
                <tr>
                    <td>
                        <a href="/domains/{{ $domain->id }}/dns-records" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                            {{ $domain->domain_name }}
                        </a>
                    </td>
                    <td style="text-align: right; padding-right: 25px; font-weight: 600;">
                        {{ $domain->dns_records_count ?? 0 }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="color: #94a3b8; text-align: center; padding: 20px 0;">No domains have been added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="blok" style="background-color: #ffffff; flex: 1; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 16px; color: #0f172a; margin-bottom: 20px;">Recent Logs</h2>
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th style="text-align: right; padding-right: 10px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sonIslemler as $log)
                <tr>
                    <td style="font-weight: 500; color: #334155;">{{ $log->action }}</td>
                    <td style="color: #64748b; text-align: right; padding-right: 10px;">
                        <i class="fa-regular fa-clock" style="font-size: 12px; margin-right: 4px; color: #94a3b8;"></i>
                        {{ $log->created_at ? $log->created_at->format('d.m.Y H:i') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="color: #94a3b8; text-align: center; padding: 20px 0;">No system logs recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection