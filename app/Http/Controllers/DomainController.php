<?php

namespace App\Http\Controllers;
use App\Models\Log;

use App\Models\Domain;
use Illuminate\Http\Request;
use App\Services\BindService;



class DomainController extends Controller
{
    private $bindService;

    public function __construct(BindService $bindService)
    {
        $this->bindService = $bindService;
    }

    // Domainleri listele
    public function index()
    {
        if (!session('login')) {
        return redirect('/login');
    }
        $domains = Domain::all();

        return view('domainler', compact('domains'));
    }

    public function edit(int $id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $domain = Domain::findOrFail($id);

    return response()->json($domain);
}


    // Domain kaydetme işlemi
    public function store(Request $request)
    {
        if (!session('login')) {
        return redirect('/login');
    }
        $request->validate([
            'domain_name' => 'required',
            'description' => 'nullable'
        ]);

        $domain = Domain::create([
        'domain_name' => $request->domain_name,
        'description' => $request->description,
        ]);

        Log::create([
    'domain_id'   => $domain->id,
    'domain_name' => $domain->domain_name,
    'action'      => 'Domain eklendi',
    'user'        => session('user')
]);
    $this->bindService->updateNamedConf();
    //$this->bindService->reloadBind();

        return redirect('/domains');
    }

    public function destroy(int $id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $domain = Domain::findOrFail($id);

    Log::create([
        'domain_id'   => $domain->id,
        'domain_name' => $domain->domain_name,
        'action'      => 'Domain silindi',
        'user'        => session('user')
    ]);

    // Zone dosyasını sil
    $zoneFile = "/etc/bind/zones/{$domain->domain_name}.db";

    if (file_exists($zoneFile)) {
        unlink($zoneFile);
    }

    // Domaini sil
    $domain->delete();

    // zones.conf'u güncelle
    $this->bindService->updateNamedConf();
    //$this->bindService->reloadBind();

    return redirect('/domains');
}

    public function update(Request $request, int $id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $request->validate([
        'domain_name' => 'required',
        'description' => 'nullable'
    ]);

    $domain = Domain::findOrFail($id);
    
    $oldDomainName = $domain->domain_name; // Eski domain adını sakla   
   
    $domain->update([
        'domain_name' => $request->domain_name,
        'description' => $request->description
    ]);
    
    $oldZoneFile = "/etc/bind/zones/{$oldDomainName}.db";

       if ($oldDomainName != $domain->domain_name && file_exists($oldZoneFile)) {
       unlink($oldZoneFile);
} // Eski zone dosyasını sil
    
    $this->bindService->updateNamedConf();
    $this->bindService->generateZoneFiles();
    //$this->bindService->reloadBind();
    return redirect('/domains');
}
    
}