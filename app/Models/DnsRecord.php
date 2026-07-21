<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DnsRecord extends Model
{
    protected $fillable = [
    'domain_id',
    'host',
    'type',
    'value',
    'internal_ip',
    'external_ip',
    'ttl',
];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}