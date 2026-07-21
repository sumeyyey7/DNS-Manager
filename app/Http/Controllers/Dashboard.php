<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DnsRecord;
use App\Models\Log;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function dashboard()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $domainCount = Domain::count();
        $dnsRecordCount = DnsRecord::count();
        $userCount = Log::distinct('user')->count('user');

        $islemSayisi = Log::where(
            'created_at',
            '>=',
            Carbon::now()->subDay()
        )->count();

        $sonDomainler = Domain::withCount('dnsRecords')
            ->latest()
            ->take(3)
            ->get();

        $sonIslemler = Log::latest()
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'domainCount',
            'dnsRecordCount',
            'userCount',
            'islemSayisi',
            'sonDomainler',
            'sonIslemler'
        ));
    }
}