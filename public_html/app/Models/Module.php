<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
     protected $table = 'modulos';
    protected $primaryKey = 'id';

    protected $fillable = ['nombre'];

    public function views()
    {
        return $this->belongsToMany(View::class, 'modulos_vistas', 'modulo_id', 'vista_id');
    }
}
