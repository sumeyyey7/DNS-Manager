<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    // Domainleri listele
    public function index()
    {
        $domains = Domain::all();

        return view('index', compact('domains'));
    }

    // Domain ekleme sayfasını aç
    public function create()
    {
        return view('create');
    }

    // Domaini kaydet
    public function store(Request $request)
    {
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
}