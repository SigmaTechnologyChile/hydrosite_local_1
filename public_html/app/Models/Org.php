<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'rut',
        'razon_social',
        'logo',
        'state_id',
        'city_id',
        'state',
        'commune',
        'name',
        'born_year',
        'starts',
        'type',
        'funds',
        'beneficiaries',
        'administration',
        'situation',
        'fantasy_name',
        'latitude',
        'longitud',
        'address',
        'active',
        'dte_access_email',
        'dte_access_password',
        'fixed_charge',
        'interest_due',
        'interest_late',
        'replacement_cut',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function getLogoUrlAttribute()
    {
        return asset($this->logo);
    }
//     public function orgPlan()
// {
//     return $this->hasOne(\App\Models\OrgPlan::class, 'id_org');
// // }
// public function orgPlan()
// {
//     return $this->hasOne(OrgPlan::class, 'id_org', 'id');
// }
public function members()
{
    return $this->belongsToMany(Member::class, 'orgs_members', 'org_id', 'member_id');
}

public function orgPlan()
{
    return $this->hasOne(OrgPlan::class, 'org_id');
}
}
