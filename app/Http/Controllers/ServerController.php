<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Services\ExternalBindService;
use Illuminate\Support\Facades\Log;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        $statuses = [];

        try {
            $external = new ExternalBindService();
            // Eğer servis yanıt vermezse sayfayı kilitletmemek için try-catch
            $statuses = $external->getServerStatus();
        } catch (\Exception $e) {
            // Sunucuya erişilemezse veya zaman aşımına uğrarsa hata fırlatmak yerine 
            // log tutup sayfayı hızlıca yükle
            Log::error('ExternalBindService bağlantı hatası: ' . $e->getMessage());
// veya
logger()->error('ExternalBindService bağlantı hatası: ' . $e->getMessage());
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
            'password'   => 'nullable|string',
        ]);

        if ($request->type === 'internal' && empty($request->password)) {
            $request->merge([
                'password' => '',
                'username' => $request->username ?: 'local'
            ]);
        }

        Server::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'ip'       => $request->ip_address,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return redirect('/servers')->with('success', 'Server başarıyla eklendi.');
    }

    public function edit($id)
    {
        $server = Server::findOrFail($id);
        return response()->json($server);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:internal,external',
            'ip_address' => 'required|ip',
            'username'   => 'required|string|max:255',
            'password'   => 'nullable|string',
        ]);

        $server = Server::findOrFail($id);

        $data = [
            'name'     => $request->name,
            'type'     => $request->type,
            'ip'       => $request->ip_address,
            'username' => $request->username,
        ];

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