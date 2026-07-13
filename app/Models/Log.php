<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
    'domain_id',
    'domain_name',
    'action',
    'user'
];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}