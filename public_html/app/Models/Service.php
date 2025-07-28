<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'id', 'org_id', 'member_id', 'nro', // agrega aquí otros campos relevantes de la tabla
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
