<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DnsRecord as DnsRecordModel;
use App\Models\Domain;

class DnsRecord extends Controller
{
    // DNS kayıtlarını listele
    public function index()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $records = DnsRecordModel::with('domain')->get();

        return view('dns-record.index', compact('records'));
    }

    // DNS kayıt ekleme sayfası
    public function create()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $domains = Domain::all();

        return view('dns-record.create', compact('domains'));
    }

    // DNS kaydı kaydet
    public function store(Request $request)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $request->validate([
            'domain_id' => 'required',
            'host'      => 'required',
            'type'      => 'required',
            'value'     => 'required',
            'ttl'       => 'required|integer'
        ]);

        DnsRecordModel::create([
            'domain_id' => $request->domain_id,
            'host'      => $request->host,
            'type'      => $request->type,
            'value'     => $request->value,
            'ttl'       => $request->ttl,
        ]);

        return redirect('/dns-records');
    }

    // DNS kaydı sil
    public function destroy($id)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $record = DnsRecordModel::findOrFail($id);

        $record->delete();

        return redirect('/dns-records');
    }
    public function dnsRecords()
{
    return $this->hasMany(DnsRecord::class);
}
}