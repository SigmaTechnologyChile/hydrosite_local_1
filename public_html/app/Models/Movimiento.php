<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    public $timestamps = false;

    protected $fillable = [
        'tipo', 'subtipo', 'fecha', 'monto', 'descripcion', 'nro_dcto',
        'categoria_id', 'cuenta_origen_id', 'cuenta_destino_id',
        'proveedor', 'rut_proveedor', 'transferencia_id', 'creado_en'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cuentaOrigen()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_origen_id');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_destino_id');
    }
}