<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'tipo', 'saldo_actual', 'banco_id', 'numero_cuenta', 'creado_en'
    ];
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(Movimiento::class, 'cuenta_origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(Movimiento::class, 'cuenta_destino_id');
    }
}