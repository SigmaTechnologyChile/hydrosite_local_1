<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conciliacion extends Model
{
    protected $table = 'conciliaciones';
    public $timestamps = false;

    protected $fillable = [
        'movimiento_id', 'fecha_conciliacion', 'estado', 'comentario'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }
}