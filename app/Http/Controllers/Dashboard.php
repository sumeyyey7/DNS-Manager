<?php

namespace App\Http\Controllers;
use App\Models\Domain;



class Dashboard extends Controller
{
    public function dashboard()
{
    $domainCount = Domain::count();

    return view('dashboard', compact('domainCount'));
}
}
