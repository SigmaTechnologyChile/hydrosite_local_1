<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $primaryKey = 'id';

    protected $fillable = ['nombre'];

    public function views()
    {
        return $this->belongsToMany(View::class, 'planes_vistas', 'plan_id', 'vista_id');
    }

    public function organizations()
    {
        return $this->hasMany(OrgPlan::class, 'id_plan');
    }
}
