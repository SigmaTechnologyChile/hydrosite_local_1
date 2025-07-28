<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedCostConfig extends Model
{
      protected $table = 'fixed_costs_config';
      public $timestamps = false;
        protected $fillable = [
        'org_id',
        'fixed_charge_penalty',
        'replacement_penalty',
        'late_fee_penalty',
        'expired_penalty',
        'max_covered_m3',
    ];

    public function tiers()
    {
        return $this->hasMany(TierConfig::class, 'org_id');
    }
    //
}
