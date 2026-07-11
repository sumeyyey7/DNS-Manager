<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    // Domainleri listele
    public function index()
    {
        if (!session('login')) {
        return redirect('/login');
    }
        $domains = Domain::all();

        return view('domainler', compact('domains'));
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

        Domain::create([
            'domain_name' => $request->domain_name,
            'description' => $request->description,
        ]);

        return redirect('/domains');
    }

    //Domain silme işlemi
    public function destroy($id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $domain = Domain::find($id);

    if (!$domain) {
        return redirect('/domains');
    }

    $domain->delete();

    return redirect('/domains');
}
    public function domainSil($id)
{
    // Veritabanından o id'ye ait domaini bul ve sil
    $domain = \App\Models\Domain::findOrFail($id);
    $domain->delete();

    // Sayfayı yenile
    return redirect()->back();
}

    public function edit($id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $domain = Domain::findOrFail($id);

    return response()->json($domain);
}
    public function update(Request $request, $id)
{
    if (!session('login')) {
        return redirect('/login');
    }

    $request->validate([
        'domain_name' => 'required',
        'description' => 'nullable'
    ]);

    $domain = Domain::findOrFail($id);

    $domain->update([
        'domain_name' => $request->domain_name,
        'description' => $request->description
    ]);

    return redirect('/domains');
}
    
    
}