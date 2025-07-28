<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
       protected $table = 'vistas';
    public $timestamps = false;
    protected $fillable = ['id','nombre'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'modulos_vistas');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'planes_vistas');
    }
}
