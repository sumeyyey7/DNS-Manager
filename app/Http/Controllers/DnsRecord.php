<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DnsRecord as DnsRecordModel;
use App\Models\Domain;
use App\Models\Log;
use App\Services\BindService;
use Illuminate\Support\Facades\Storage;

class DnsRecord extends Controller
{   
    protected $fillable = [
    'domain_id',
    'host',
    'type',
    'value',
    'internal_ip',
    'external_ip',
    'ttl',
];

    private $bindService;

    public function __construct(BindService $bindService)
    {
        $this->bindService = $bindService;
    }

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
            'domain_id' => 'required|exists:domains,id',
            'host'      => ['required', 'regex:/^(@|[a-zA-Z0-9]([a-zA-Z0-9-\.]*[a-zA-Z0-9])?)$/'],
            'type'      => 'required|in:A,AAAA,CNAME,MX,NS,TXT',
            'ttl'       => 'required|integer|min:1',
            'value' => [
    function ($attribute, $value, $fail) use ($request) {

        if (in_array($request->type, ['A', 'AAAA'])) {
            return;
        }

        if (empty($value)) {
            $fail('The value field is required.');
            return;
        }

        switch ($request->type) {

            case 'CNAME':
            case 'MX':
            case 'NS':

                if (!filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                    $fail('Please enter a valid domain name.');
                }
                break;

            case 'TXT':
                break;
        }
    }
],
        ]);

        $exists = DnsRecordModel::where('domain_id', $request->domain_id)
            ->where('host', $request->host)
            ->where('type', $request->type)
            ->where('value', $request->value)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['value' => 'This DNS record already exists.'])
                ->withInput();
        }
            
        $externalIp = $request->external_ip;

if (in_array($request->type, ['A', 'AAAA'])) {

    $natList = Storage::get('nat.txt');

    $lines = explode("\n", $natList);

    foreach ($lines as $line) {

        $line = trim($line);

        if ($line == '') {
            continue;
        }

        list($internal, $external) = explode('=', $line);

        if (trim($internal) == trim($request->internal_ip)) {

            $externalIp = trim($external);

            break;
        }
    }
}

        DnsRecordModel::create([
    'domain_id'   => $request->domain_id,
    'host'        => $request->host,
    'type'        => $request->type,
    'value'       => in_array($request->type, ['A','AAAA']) ? '' : $request->value,
    'internal_ip' => $request->internal_ip,
    'external_ip' => $externalIp,
    'ttl'         => $request->ttl,
]);

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        // Log oluştur
        $domain = Domain::findOrFail($request->domain_id);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'DNS record added',
            'user'        => session('user')
        ]);

        
        //$this->bindService->reloadBind();

        return redirect('/dns-records');
       
    }

    // DNS kaydı sil
    public function destroy(int $id)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $record = DnsRecordModel::findOrFail($id);
        $domain = Domain::findOrFail($record->domain_id);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'DNS record deleted',
            'user'        => session('user')
        ]);

        $record->delete();

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }
        //$this->bindService->reloadBind();

        return redirect('/dns-records');
    }

    public function edit(int $id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $record = DnsRecordModel::findOrFail($id);

    return response()->json($record);
}

    public function update(Request $request, int $id)
    {
        if (!session('login')) {
            return redirect('/login');
        }   
        $record = DnsRecordModel::findOrFail($id);

        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'host'      => ['required', 'regex:/^(@|[a-zA-Z0-9]([a-zA-Z0-9-\.]*[a-zA-Z0-9])?)$/'],
            'type'      => 'required|in:A,AAAA,CNAME,MX,NS,TXT',
            'ttl'       => 'required|integer|min:1',
            'value'     => [
                
                function ($attribute, $value, $fail) use ($request) {

                    switch ($request->type) {

                        case 'A':
    if (!filter_var($request->internal_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $fail('Please enter a valid Internal IPv4 address.');
    }
    break;

case 'AAAA':
    if (!filter_var($request->internal_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        $fail('Please enter a valid Internal IPv6 address.');
    }
    break;

                        case 'CNAME':
                        case 'MX':
                        case 'NS':
                            if (!filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                                $fail('Please enter a valid domain name.');
                            }
                            break;

                        case 'TXT':
                            // TXT kayıtlarında ek kontrol yok
                            break;
                    }
                }
            ]
            
            
        ]);
        

        $exists = DnsRecordModel::where('domain_id', $request->domain_id)
            ->where('host', $request->host)
            ->where('type', $request->type)
            ->where('value', $request->value)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['value' => 'This DNS record already exists.'])
                ->withInput();
        }

        $record->update([
            'domain_id' => $request->domain_id,
            'host'      => $request->host,
            'type'      => $request->type,
            'value' => in_array($request->type, ['A', 'AAAA'])? "": $request->value,
            'internal_ip' => $request->internal_ip,
            'external_ip' => $request->external_ip,
            'ttl'       => $request->ttl,
        ]);

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        $domain = Domain::findOrFail($request->domain_id);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'DNS record updated',
            'user'        => session('user')
        ]);

        
        //$this->bindService->reloadBind();

        return redirect('/dns-records');
    }
    public function findNatIp(Request $request)
{
    $externalIp = "";

    if (Storage::exists('nat.txt')) {

        $lines = file(storage_path('app/nat.txt'));

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line == '') {
                continue;
            }

            list($internal, $external) = explode('=', $line);

            if (trim($internal) == trim($request->internal_ip)) {

                $externalIp = trim($external);
                break;
            }
        }
    }

    return response()->json([
        'external_ip' => $externalIp
    ]);
}
}