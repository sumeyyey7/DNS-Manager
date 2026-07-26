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
            // Forward zone
            $content .= 'zone "' . $domain->domain_name . "\" {\n";
            $content .= "    type master;\n";
            $content .= '    file "/etc/bind/zones/' . $domain->domain_name . ".db\";\n";
            $content .= "};\n\n";

            // Reverse zone (Öncelik Dış IP'de)
            foreach ($domain->dnsRecords as $record) {
                $externalIp = trim($record->external_ip ?? '');
                $internalIp = trim($record->internal_ip ?? '');

                // Öncelik external_ip, yoksa internal_ip
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

        // Yerel tarafta zones.conf dosyasının named.conf.local içine eklendiğinden emin olunur
        exec('grep -q \'include "/etc/bind/zones.conf";\' /etc/bind/named.conf.local || echo \'include "/etc/bind/zones.conf";\' | sudo tee -a /etc/bind/named.conf.local');
    }

    public function generateZoneFiles()
    {
        // Eski tüm zone dosyalarını temizle
        $files = glob('/etc/bind/zones/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Domainleri al
        $domains = Domain::with('dnsRecords')->get();
        $serial = date('Ymd') . '01';

        // Tüm reverse kayıtları burada toplanacak
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

                    // Forward zone için External IP kullan (Yoksa internal IP'ye geç)
                    if (filter_var($externalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $value = $externalIp;
                    } elseif (filter_var($internalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $value = $internalIp;
                    }

                    // Reverse zone için IP belirle (ÖNCELİK EXTERNAL IP)
                    $targetIp = filter_var($internalIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
    ? $internalIp
    : null;
    
                    if ($targetIp) {
                        $parts = explode('.', $targetIp);
                        if (count($parts) === 4) {
                            $zoneName = "{$parts[2]}.{$parts[1]}.{$parts[0]}.in-addr.arpa";
                            $ptrHost = $parts[3];
                            
                            $recordHost = ($record->host === '@') ? '' : ".{$record->host}";
                            $fqdn = "{$domain->domain_name}{$recordHost}.";

                            $reverseZones[$zoneName][] = [
                                'host' => $ptrHost,
                                'domain' => $fqdn
                            ];
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

            // Forward zone dosyasını yaz
            file_put_contents(
                "/etc/bind/zones/{$domain->domain_name}.db",
                $content
            );
        }

        // Reverse zone dosyalarını oluştur
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
        // Yerel BIND yapılandırmasını oluştur
        $this->updateNamedConf();

        // Zone dosyalarını oluştur
        $this->generateZoneFiles();

        // named.conf kontrolü
        $conf = $this->checkNamedConf();

        if (!$conf['success']) {
            return $conf;
        }

        // Zone dosyalarını doğrula
        $zones = $this->checkAllZones();

        if (!$zones['success']) {
            return $zones;
        }

        // Yerel BIND'i yeniden yükle
        $local = $this->reloadBind();

        if (!$local['success']) {
            return $local;
        }

        // External sunucular
        $external = new \App\Services\ExternalBindService();

        /*
        |--------------------------------------------------------------------------
        | Dosyaları tüm external sunuculara gönder
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Tüm external sunucularda named-checkconf
        |--------------------------------------------------------------------------
        */
        $checkResults = $external->checkNamedConf();

        foreach ($checkResults as $check) {
            if (!$check['result']['success']) {
                return [
                    'success' => false,
                    'message' => "named-checkconf başarısız.\nSunucu: {$check['server']}\n{$check['result']['message']}"
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Tüm external sunucularda rndc reload
        |--------------------------------------------------------------------------
        */
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