@extends('layouts.app')

@section('content')
<div class="sayfa-baslik">
    <h1>Anasayfa</h1>
    <p>Sistem özeti ve istatistikler</p>
</div>

<div class="kartlar-kapsayici">
    <div class="kart">
        <div class="kart-bilgi">
            <h3>Toplam Domain</h3>
            <div class="sayi">{{ $domainCount }}</div>
            <div class="alt-yazi">Aktif domain sayısı</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-globe"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Toplam DNS Kaydı</h3>
            <div class="sayi">{{ $dnsRecordCount }}</div>
            <div class="alt-yazi">Tüm kayıtların toplamı</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-database"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Toplam Kullanıcı</h3>
            <div class="sayi">{{ $userCount }}</div>
            <div class="alt-yazi">Sistemdeki kullanıcı sayısı</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-users"></i></div>
    </div>

    <div class="kart">
        <div class="kart-bilgi">
            <h3>Son 24 Saat İşlem</h3>
            <div class="sayi">{{ $islemSayisi ?? 0 }}</div>
            <div class="alt-yazi">Son 24 saatteki işlem sayısı</div>
        </div>
        <div class="kart-ikon"><i class="fa-solid fa-chart-line"></i></div>
    </div>
</div>

<div class="alt-blocks" style="display: flex; gap: 20px;">

    <div class="blok" style="background-color: #ffffff; flex: 1; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 16px; color: #0f172a; margin-bottom: 20px;">Son Eklenen Domainler</h2>
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th style="text-align: right; padding-right: 10px;">Kayıt Sayısı</th>
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
                    <td colspan="2" style="color: #94a3b8; text-align: center; padding: 20px 0;">Henüz eklenmiş bir domain bulunmuyor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="blok" style="background-color: #ffffff; flex: 1; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 16px; color: #0f172a; margin-bottom: 20px;">Son İşlemler</h2>
        <table>
            <thead>
                <tr>
                    <th>İşlem</th>
                    <th style="text-align: right; padding-right: 10px;">Tarih</th>
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
                    <td colspan="2" style="color: #94a3b8; text-align: center; padding: 20px 0;">Henüz bir sistem hareketi kaydedilmedi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection