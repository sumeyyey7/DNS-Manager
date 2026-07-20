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
        if (
            $request->email === "syesilyurt589@gmail.com" &&
            $request->password === "123456"
        ) {
            $request->session()->put('login', true);
            $request->session()->put('user', $request->email);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        // Flush or forget the session data to properly log out
        $request->session()->forget(['login', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}