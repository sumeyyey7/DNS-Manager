<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DnsRecord as DnsRecordModel;
use App\Models\Domain;
use App\Models\Log;

class DnsRecord extends Controller
{
    // DNS kayıtlarını listele
    public function index()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $records = DnsRecordModel::with('domain')->get();
        $domains = Domain::all();

        return view('dns-record.index', compact('records', 'domains'));
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

        // Log oluştur
        Log::create([
            'domain_id' => $request->domain_id,
            'action'    => 'DNS kaydı eklendi',
            'user'      => session('user')
        ]);

        return redirect('/dns-records');
    }

    // DNS kaydı sil
    public function destroy(int $id)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $record = DnsRecordModel::findOrFail($id);

        Log::create([
            'domain_id' => $record->domain_id,
            'action'    => 'DNS kaydı silindi',
            'user' => session('user')// Burada kullanıcı adını session'dan alabilirsiniz
        ]);

        $record->delete();

        return redirect('/dns-records');
    }
        public function edit(int$id)
{
    $record = DnsRecordModel::findOrFail($id);

    return response()->json($record);
}

public function update(Request $request, int $id)
{
    $record = DnsRecordModel::findOrFail($id);

    $record->update([
        'domain_id' => $request->domain_id,
        'host'      => $request->host,
        'type'      => $request->type,
        'value'     => $request->value,
        'ttl'       => $request->ttl,
    ]);

    return redirect('/dns-records');
}
}