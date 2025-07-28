<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgPlan extends Model
{
    protected $table = 'org_planes';
    protected $primaryKey = 'id';

    protected $fillable = ['id_org', 'id_plan'];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'id_plan');
    }


}
