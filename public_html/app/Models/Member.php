<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    public function services()
{
    //return $this->hasMany(Service::class, 'member_id');
    return $this->hasMany(Service::class, 'rut', 'rut');
}
public function orgs()
{
    return $this->belongsToMany(Org::class, 'orgs_members', 'member_id', 'org_id');
}
public function readings()
{
    return $this->hasMany(Reading::class);
}
}
