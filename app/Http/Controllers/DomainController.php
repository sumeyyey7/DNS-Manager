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

    // Domain ekleme
    public function store(Request $request)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $request->validate([
            'domain_name' => 'required|string|max:255|unique:domains,domain_name',
            'description' => 'nullable'
        ]);

        $domain = Domain::create([
            'domain_name' => $request->domain_name,
            'description' => $request->description,
            'status' => 'active'
        ]);

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {

            $domain->update([
                'status' => 'error'
            ]);

            return back()->with('error', $result['message']);
        }

        $domain->update([
            'status' => 'active'
        ]);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'Domain added',
            'user'        => session('user')
        ]);

        return redirect('/domains');
    }

    // Domain silme
    public function destroy(int $id)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $domain = Domain::findOrFail($id);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'Domain deleted',
            'user'        => session('user')
        ]);

        $zoneFile = "/etc/bind/zones/{$domain->domain_name}.db";

        if (file_exists($zoneFile)) {
            unlink($zoneFile);
        }

        $domain->delete();

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect('/domains');
    }

    // Domain güncelleme
    public function update(Request $request, int $id)
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $request->validate([
            'domain_name' => 'required|string|max:255|unique:domains,domain_name,' . $id,
            'description' => 'nullable'
        ]);

        $domain = Domain::findOrFail($id);

        $oldDomainName = $domain->domain_name;

        $domain->update([
            'domain_name' => $request->domain_name,
            'description' => $request->description
        ]);

        $oldZoneFile = "/etc/bind/zones/{$oldDomainName}.db";

        if ($oldDomainName != $domain->domain_name && file_exists($oldZoneFile)) {
            unlink($oldZoneFile);
        }

        $result = $this->bindService->applyChanges();

        if (!$result['success']) {

            $domain->update([
                'status' => 'error'
            ]);

            return back()->with('error', $result['message']);
        }

        $domain->update([
            'status' => 'active'
        ]);

        Log::create([
            'domain_id'   => $domain->id,
            'domain_name' => $domain->domain_name,
            'action'      => 'Domain updated',
            'user'        => session('user')
        ]);

        return redirect('/domains');
    }
}