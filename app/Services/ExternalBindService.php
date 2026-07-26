<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Database\Eloquent\Collection;

class ExternalBindService
{

    private Collection $servers;

public function __construct()
{
    $this->servers = Server::where('type', 'external')->get();

    if ($this->servers->isEmpty()) {
        throw new \Exception('No external servers found.');
    }
}

    /**
     * VM üzerinde komut çalıştırır.
     */
    private function run(Server $server, string $command): array
{
    $output = [];
    $result = 0;

    $cmd =
        "sshpass -p '{$server->password}' " .
        "ssh -o StrictHostKeyChecking=no {$server->username}@{$server->ip} " .
        escapeshellarg($command) .
        " 2>&1";

    exec($cmd, $output, $result);

    return [
        'success' => $result === 0,
        'code'    => $result,
        'message' => implode("\n", $output),
        'command' => $cmd,
    ];
}

    public function testConnection(): array
{
    $results = [];

    foreach ($this->servers as $server) {
        $results[] = [
            'server' => $server->name,
            'result' => $this->run($server, 'hostname')
        ];
    }

    return $results;
}

    public function reloadBind(): array
{
    $results = [];

    foreach ($this->servers as $server) {
        $results[] = [
            'server' => $server->name,
            'result' => $this->run($server, 'sudo rndc reload')
        ];
    }

    return $results;
}

    public function checkNamedConf(): array
{
    $results = [];

    foreach ($this->servers as $server) {
        $results[] = [
            'server' => $server->name,
            'result' => $this->run($server, 'sudo named-checkconf')
        ];
    }

    return $results;
}

    public function uploadZoneFiles()
    {
        $sourcePath = "/etc/bind/zones";
        $tempPath = storage_path("app/upload-zones");

        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // Yerel geçici klasörü temizle
        foreach (glob($tempPath . "/*") as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Zone dosyalarını kopyala
        foreach (glob($sourcePath . "/*") as $file) {
            if (is_file($file)) {
                copy($file, $tempPath . "/" . basename($file));
            }
        }

        if (file_exists("/etc/bind/zones.conf")) {
            copy("/etc/bind/zones.conf", $tempPath . "/zones.conf");
        }

        $results = [];

foreach ($this->servers as $server) {

    $this->run($server, "rm -rf /tmp/bind_upload && mkdir -p /tmp/bind_upload");

    $output = [];
    $result = 0;

    $cmd =
        "sshpass -p '{$server->password}' scp -o StrictHostKeyChecking=no -r " .
        $tempPath . "/* " .
        "{$server->username}@{$server->ip}:/tmp/bind_upload/ 2>&1";

    exec($cmd, $output, $result);

    if ($result !== 0) {

        $results[] = [
            'server' => $server->name,
            'success' => false,
            'message' => implode("\n", $output)
        ];

        continue;
    }

    $results[] = [
        'server' => $server->name,
        'result' => $this->run($server, "
            sudo mkdir -p /etc/bind/zones &&
            sudo rm -f /etc/bind/zones/* &&
            sudo cp /tmp/bind_upload/*.db /etc/bind/zones/ 2>/dev/null;
            sudo cp /tmp/bind_upload/zones.conf /etc/bind/zones.conf 2>/dev/null;
            sudo grep -q 'include \"/etc/bind/zones.conf\";' /etc/bind/named.conf.local || echo 'include \"/etc/bind/zones.conf\";' | sudo tee -a /etc/bind/named.conf.local;
            sudo chown -R bind:bind /etc/bind/zones /etc/bind/zones.conf /etc/bind/named.conf.local;
            sudo chmod 644 /etc/bind/zones/* /etc/bind/zones.conf
        ")
    ];
}

return $results;

        
    }
    public function getServerStatus(): array
{
    $statuses = [];

    foreach ($this->servers as $server) {

        $result = $this->run($server, "hostname");

        $statuses[$server->id] = $result['success'];
    }

    return $statuses;
}
}