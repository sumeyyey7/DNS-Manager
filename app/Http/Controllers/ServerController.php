<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Services\ExternalBindService;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        $statuses = [];

        try {
            $external = new ExternalBindService();
            $statuses = $external->getServerStatus();
        } catch (\Exception $e) {
            // External sunucu yoksa boş bırak
        }

        return view('servers', compact('servers', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:internal,external',
            'ip_address' => 'required|ip',
            'username'   => 'required|string|max:255',
            'password'   => 'nullable|string', // Zorunluluğu esnettik
        ]);

        if ($request->type === 'internal' && empty($request->password)) {
    $request->merge([
        'password' => '',
        'username' => $request->username ?: 'local'
    ]);
}

        Server::create([
            'name'       => $request->name,
            'type'       => $request->type,
            'ip' => $request->ip_address, // Veritabanı sütun adına dikkat (ip vs ip_address)
            'username'   => $request->username,
            'password'   => $request->password,
        ]);

        return redirect('/servers')->with('success', 'Server başarıyla eklendi.');
    }

    // Modal düzenleme (Edit) açıldığında JavaScript'e verileri döndüren metot
    public function edit($id)
    {
        $server = Server::findOrFail($id);
        return response()->json($server);
    }

    // Güncelleme isteğini karşılayan metot (Eksik olan kısım buydu)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:internal,external',
            'ip_address' => 'required|ip',
            'username'   => 'required|string|max:255',
            'password'   => 'nullable|string', // Güncellerken şifre girmek opsiyonel
        ]);

        $server = Server::findOrFail($id);

        $data = [
            'name'       => $request->name,
            'type'       => $request->type,
            'ip' => $request->ip_address,
            'username'   => $request->username,
        ];

        // Eğer kullanıcı yeni bir şifre girdiyse güncelle, girmediyse eski şifreyi koru
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $server->update($data);

        return redirect('/servers')->with('success', 'Server başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $server->delete();

        return redirect('/servers')->with('success', 'Server silindi.');
    }
}