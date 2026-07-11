<?php

namespace App\Http\Controllers;

use App\Models\Domain;

class Dashboard extends Controller
{
    public function dashboard()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $domainCount = Domain::count();

        return view('dashboard', compact('domainCount'));
    }
}