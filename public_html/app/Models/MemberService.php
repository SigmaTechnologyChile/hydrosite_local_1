<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberService extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'mide_plan',
        'porcentaje',
        'tipo_medidor',
        'num_medidor',
        'propietario',
        'boleta_factura',
        'diametro',
        'observaciones',
    ];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

