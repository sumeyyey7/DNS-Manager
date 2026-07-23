<?php

namespace App\Services;

class ExternalBindService
{
    private string $host = "192.168.56.101";
    private string $user = "sumeeye";
    private string $password = "Ubuntu/200800";

    /**
     * VM üzerinde komut çalıştırır.
     */
    private function run(string $command): array
    {
        $output = [];
        $result = 0;

        $cmd =
            "sshpass -p '{$this->password}' " .
            "ssh -o StrictHostKeyChecking=no {$this->user}@{$this->host} " .
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

    public function testConnection()
    {
        return $this->run("hostname");
    }

    public function reloadBind()
    {
        return $this->run("sudo rndc reload");
    }

    public function checkNamedConf()
    {
        return $this->run("sudo named-checkconf");
    }

    public function uploadZoneFiles()
{
    $path = storage_path('app/external-zones');

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    foreach (glob($path . '/*.db') as $file) {
        unlink($file);
    }

    $domains = \App\Models\Domain::with('dnsRecords')->get();

    $zonesConf = "";

    foreach ($domains as $domain) {

        $serial = date('Ymd') . '01';

        $content = '$TTL 604800' . PHP_EOL . PHP_EOL;

        $content .= "@ IN SOA ns1.{$domain->domain_name}. admin.{$domain->domain_name}. (" . PHP_EOL;
        $content .= "    {$serial}" . PHP_EOL;
        $content .= "    604800" . PHP_EOL;
        $content .= "    86400" . PHP_EOL;
        $content .= "    2419200" . PHP_EOL;
        $content .= "    604800 )" . PHP_EOL . PHP_EOL;

        $content .= "@ IN NS ns1.{$domain->domain_name}." . PHP_EOL;
        $content .= "ns1 IN A 127.0.0.1" . PHP_EOL;

        foreach ($domain->dnsRecords as $record) {

            $value = $record->value;

            if ($record->type === 'A' && !empty($record->external_ip)) {
                $value = $record->external_ip;
            }

            $content .= sprintf(
                "%-15s IN %-6s %s\n",
                $record->host,
                $record->type,
                $value
            );
        }

        file_put_contents(
            "{$path}/{$domain->domain_name}.db",
            $content
        );

        $zonesConf .= 'zone "' . $domain->domain_name . "\" {\n";
        $zonesConf .= "    type master;\n";
        $zonesConf .= '    file "/etc/bind/zones/' . $domain->domain_name . ".db\";\n";
        $zonesConf .= "};\n\n";
    }

    file_put_contents("{$path}/zones.conf", $zonesConf);

    $output = [];
    $result = 0;

    $cmd =
        "sshpass -p '{$this->password}' scp -o StrictHostKeyChecking=no " .
        "{$path}/*.db " .
        "{$this->user}@{$this->host}:/tmp/ 2>&1";

    exec($cmd, $output, $result);

    if ($result !== 0) {
        return [
            'success' => false,
            'message' => implode("\n", $output),
        ];
    }

    $output = [];
    $result = 0;

    $cmd =
        "sshpass -p '{$this->password}' scp -o StrictHostKeyChecking=no " .
        "{$path}/zones.conf " .
        "{$this->user}@{$this->host}:/tmp/ 2>&1";

    exec($cmd, $output, $result);

    if ($result !== 0) {
        return [
            'success' => false,
            'message' => implode("\n", $output),
        ];
    }

    return $this->run("
        sudo mkdir -p /etc/bind/zones &&
        sudo cp /tmp/*.db /etc/bind/zones/ &&
        sudo cp /tmp/zones.conf /etc/bind/zones.conf
    ");
}
}