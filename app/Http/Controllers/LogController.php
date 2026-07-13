<?php

namespace App\Http\Controllers;

use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        if (!session('login')) {
            return redirect('/login');
        }

        $logs = Log::latest()->get();

        return view('log', compact('logs'));
    }
}