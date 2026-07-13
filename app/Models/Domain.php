<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
    'domain_name',
    'description'
];
    public function dnsRecords()
{
    return $this->hasMany(DnsRecord::class);
}
}
