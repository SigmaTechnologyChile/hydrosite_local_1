<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'tipo', 'grupo'
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}