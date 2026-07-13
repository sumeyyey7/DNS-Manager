<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Dnsmanage extends Controller
{
    public function login(){

        return view('login');
    }

    public function authenticate(Request $request)
{
    if (
        $request->email == "syesilyurt589@gmail.com" &&
        $request->password == "123456"
    ) {
        $request->session()->put('login', true);
        $request->session()->put('user', $request->email);

        return redirect('/dashboard');
    }

    return back()->with('error', 'E-posta veya şifre hatalı.');
}
    public function logout(Request $request)
{
    $request->session()->put('login', true);
    $request->session()->put('user', $request->email);

    return redirect('/login');
}
}
