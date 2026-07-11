<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DnsRecord extends Model
{
    protected $fillable = [
        'domain_id',
        'type',
        'value',
        'ttl'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
