<?php

namespace App\Services;

use App\Models\Domain;

class BindService
{
    public function updateNamedConf()
    {
        $domains = Domain::all();

        $content = "";

        foreach ($domains as $domain) {

            $content .= 'zone "' . $domain->domain_name . "\" {\n";
            $content .= "    type master;\n";
            $content .= '    file "/etc/bind/zones/' . $domain->domain_name . ".db\";\n";
            $content .= "};\n\n";
        }

        file_put_contents('/etc/bind/zones.conf', $content);
    }

    public function generateZoneFiles()
    {
        // Önce eski zone dosyalarını sil
        $files = glob('/etc/bind/zones/*.db');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Güncel domainleri al
        $domains = Domain::with('dnsRecords')->get();

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

                $content .= sprintf(
                    "%-15s IN %-6s %s\n",
                    $record->host,
                    $record->type,
                    $record->value
                );
            }

            file_put_contents(
                "/etc/bind/zones/{$domain->domain_name}.db",
                $content
            );
        }
    }

    public function reloadBind()
    {
        exec('sudo rndc reload 2>&1', $output, $result);

        return [
            'success' => $result === 0,
            'message' => implode("\n", $output)
        ];
    }

    public function checkNamedConf()
    {
        exec('sudo named-checkconf 2>&1', $output, $result);

        return [
            'success' => $result === 0,
            'message' => implode("\n", $output)
        ];
    }

    public function checkAllZones()
    {
        $domains = Domain::all();

        foreach ($domains as $domain) {

            $output = [];

            exec(
                "sudo named-checkzone {$domain->domain_name} /etc/bind/zones/{$domain->domain_name}.db 2>&1",
                $output,
                $result
            );

            if ($result !== 0) {
                return [
                    'success' => false,
                    'message' => implode("\n", $output)
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Tüm zone dosyaları doğrulandı.'
        ];
    }

    public function applyChanges()
    {
        $this->updateNamedConf();

        $this->generateZoneFiles();

        $conf = $this->checkNamedConf();

        if (!$conf['success']) {
            return $conf;
        }

        $zones = $this->checkAllZones();

        if (!$zones['success']) {
            return $zones;
        }

        return $this->reloadBind();
    }
}