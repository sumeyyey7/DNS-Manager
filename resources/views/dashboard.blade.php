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
        <div class="kart-ikon">
            <i class="fa-solid fa-globe"></i>
        </div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Total DNS Records</h3>
            <div class="sayi">{{ $dnsRecordCount }}</div>
            <div class="alt-yazi">Total number of records</div>
        </div>
        <div class="kart-ikon">
            <i class="fa-solid fa-database"></i>
        </div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Total Users</h3>
            <div class="sayi">{{ $userCount ?? 0 }}</div>
            <div class="alt-yazi">Number of registered users</div>
        </div>
        <div class="kart-ikon">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Last 24 Hours Activity</h3>
            <div class="sayi">{{ $islemSayisi ?? 0 }}</div>
            <div class="alt-yazi">Actions in the last 24 hours</div>
        </div>
        <div class="kart-ikon">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>

</div>

<div class="alt-bloklar">

    <div class="blok">

        <h2>Recently Added Domains</h2>

        <table>

            <thead>
                <tr>
                    <th>Domain</th>
                    <th style="text-align:right;padding-right:10px;">Record Count</th>
                </tr>
            </thead>

            <tbody>

                @forelse($sonDomainler as $domain)

                    <tr>

                        <td>
                            <a href="{{ url('/domains/'.$domain->id.'/dns-records') }}">
                                {{ $domain->domain_name }}
                            </a>
                        </td>

                        <td style="text-align:right;padding-right:25px;font-weight:600;">
                            {{ $domain->dns_records_count ?? 0 }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2" style="text-align:center;color:#94a3b8;padding:20px;">
                            No domains have been added yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="blok">

        <h2>Recent Logs</h2>

        <table>

            <thead>
                <tr>
                    <th>Action</th>
                    <th style="text-align:right;padding-right:10px;">Date</th>
                </tr>
            </thead>

            <tbody>

                @forelse($sonIslemler as $log)

                    <tr>

                        <td style="font-weight:500;">
                            {{ $log->action }}
                        </td>

                        <td style="text-align:right;padding-right:10px;color:#64748b;">
                            <i class="fa-regular fa-clock" style="font-size:12px;margin-right:4px;"></i>
                            {{ $log->created_at?->format('d.m.Y H:i') ?? '—' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2" style="text-align:center;color:#94a3b8;padding:20px;">
                            No system logs recorded yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection