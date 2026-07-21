<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dnsmanage extends Controller
{
    public function login()
{
    return view('login');
}

    public function authenticate(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $mailbox = "{ogrencimail.ibu.edu.tr:993/imap/ssl}INBOX";
        // $mailbox = "{imap.atauni.edu.tr:993/imap/ssl}INBOX";
        
        $imap = @imap_open(
        $mailbox,
        $request->email,
        $request->password
);

        

        if ($imap) {

            imap_close($imap);

            $request->session()->put('login', true);
            $request->session()->put('user', $request->email);

            return redirect('/dashboard');
        }

        return back()
            ->withInput()
            ->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['login', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}