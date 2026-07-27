<?php

namespace App\Services;

use App\Models\Domain;

class BindService
{
    public function updateNamedConf()
    {
        $domains = Domain::with('dnsRecords')->get();
        $content = "";
        $addedReverseZones = [];

        foreach ($domains as $domain) {
    
            $content .= 'zone "' . $domain->domain_name . "\" {\n";
            $content .= "    type master;\n";
            $content .= '    file "/etc/bind/zones/' . $domain->domain_name . ".db\";\n";
            $content .= "};\n\n";

            
            foreach ($domain->dnsRecords as $record) {
                $externalIp = trim($record->external_ip ?? '');
                $internalIp = trim($record->internal_ip ?? '');

                $ip = filter_var($externalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) 
                    ? $externalIp 
                    : (filter_var($internalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $internalIp : null);

                if (strtoupper($record->type) === 'A' && $ip) {
                    $parts = explode('.', $ip);

                    if (count($parts) === 4) {
                        $reverseZone = "{$parts[2]}.{$parts[1]}.{$parts[0]}.in-addr.arpa";

                        if (!in_array($reverseZone, $addedReverseZones)) {
                            $addedReverseZones[] = $reverseZone;

                            $content .= 'zone "' . $reverseZone . "\" {\n";
                            $content .= "    type master;\n";
                            $content .= '    file "/etc/bind/zones/' . $reverseZone . ".db\";\n";
                            $content .= "};\n\n";
                        }
                    }
                }
            }
        }

        file_put_contents('/etc/bind/zones.conf', $content);

      
        exec('grep -q \'include "/etc/bind/zones.conf";\' /etc/bind/named.conf.local || echo \'include "/etc/bind/zones.conf";\' | sudo tee -a /etc/bind/named.conf.local');
    }

    public function generateZoneFiles()
{
   
    $files = glob('/etc/bind/zones/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    $domains = Domain::with('dnsRecords')->get();
    $serial = date('Ymd') . '01';
    $reverseZones = [];

    foreach ($domains as $domain) {
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

            if (strtoupper($record->type) === 'A') {
                $externalIp = trim($record->external_ip ?? '');
                $internalIp = trim($record->internal_ip ?? '');

           
                $targetIp = filter_var($externalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
                    ? $externalIp
                    : (filter_var($internalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $internalIp : null);

                if ($targetIp) {
                    $value = $targetIp;

                    $parts = explode('.', $targetIp);
                    if (count($parts) === 4) {
                        $zoneName = "{$parts[2]}.{$parts[1]}.{$parts[0]}.in-addr.arpa";
                        $ptrHost = $parts[3];
                        
                    
                        $hostName = trim($record->host);
                        if ($hostName === '@' || empty($hostName)) {
                            $fqdn = "{$domain->domain_name}.";
                        } else {
                            $fqdn = "{$hostName}.{$domain->domain_name}.";
                        }

                     
                        $exists = false;
                        if (isset($reverseZones[$zoneName])) {
                            foreach ($reverseZones[$zoneName] as $existingRecord) {
                                if ($existingRecord['host'] === $ptrHost && $existingRecord['domain'] === $fqdn) {
                                    $exists = true;
                                    break;
                                }
                            }
                        }

                        if (!$exists) {
                            $reverseZones[$zoneName][] = [
                                'host' => $ptrHost,
                                'domain' => $fqdn
                            ];
                        }
                    }
                }
            }

            $content .= sprintf(
                "%-15s IN %-6s %s\n",
                $record->host,
                $record->type,
                $value
            );
        }

        
        file_put_contents(
            "/etc/bind/zones/{$domain->domain_name}.db",
            $content
        );
    }

    
    foreach ($reverseZones as $zoneName => $records) {
        $firstDomain = rtrim($records[0]['domain'], '.');
        $parts = explode('.', $firstDomain);

        if (count($parts) >= 2) {
            $baseDomain = implode('.', array_slice($parts, -2));
        } else {
            $baseDomain = $firstDomain;
        }

        $reverseContent = '$TTL 604800' . PHP_EOL . PHP_EOL;

        $reverseContent .= "@ IN SOA ns1.{$baseDomain}. admin.{$baseDomain}. (" . PHP_EOL;
        $reverseContent .= "    {$serial}" . PHP_EOL;
        $reverseContent .= "    604800" . PHP_EOL;
        $reverseContent .= "    86400" . PHP_EOL;
        $reverseContent .= "    2419200" . PHP_EOL;
        $reverseContent .= "    604800 )" . PHP_EOL . PHP_EOL;

        $reverseContent .= "@ IN NS ns1.{$baseDomain}." . PHP_EOL . PHP_EOL;

        foreach ($records as $ptr) {
            $reverseContent .= sprintf(
                "%-10s IN PTR %s\n",
                $ptr['host'],
                $ptr['domain']
            );
        }

        file_put_contents(
            "/etc/bind/zones/{$zoneName}.db",
            $reverseContent
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
        $zoneFiles = glob('/etc/bind/zones/*.db');

        foreach ($zoneFiles as $file) {
            $zoneName = basename($file, '.db');
            $output = [];

            exec(
                "sudo named-checkzone {$zoneName} {$file} 2>&1",
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

        $local = $this->reloadBind();
        if (!$local['success']) {
            return $local;
        }

        $external = new \App\Services\ExternalBindService();

        
        $uploadResults = $external->uploadZoneFiles();
        foreach ($uploadResults as $upload) {
            if (isset($upload['result']) && !$upload['result']['success']) {
                return [
                    'success' => false,
                    'message' => "Dosya aktarımı başarısız.\nSunucu: {$upload['server']}\n{$upload['result']['message']}"
                ];
            } elseif (isset($upload['success']) && !$upload['success']) {
                return [
                    'success' => false,
                    'message' => "Dosya aktarımı başarısız.\nSunucu: {$upload['server']}\n{$upload['message']}"
                ];
            }
        }

        $checkResults = $external->checkNamedConf();
        foreach ($checkResults as $check) {
            if (!$check['result']['success']) {
                return [
                    'success' => false,
                    'message' => "named-checkconf başarısız.\nSunucu: {$check['server']}\n{$check['result']['message']}"
                ];
            }
        }

        
        $reloadResults = $external->reloadBind();
        foreach ($reloadResults as $reload) {
            if (!$reload['result']['success']) {
                return [
                    'success' => false,
                    'message' => "rndc reload başarısız.\nSunucu: {$reload['server']}\n{$reload['result']['message']}"
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Yerel DNS ve tüm external DNS sunucuları başarıyla güncellendi.'
        ];
    }
}