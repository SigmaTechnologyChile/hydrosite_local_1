<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(State::class);
    }


    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
