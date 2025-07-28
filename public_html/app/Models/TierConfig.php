<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TierConfig extends Model
{
    //

      protected $table = 'tier_config';

      public $timestamps = false;
    protected $fillable = [
        'org_id',
        'tier_name',
        'range_from',
        'range_to',
        'value',
    ];

    public function config()
    {
        return $this->belongsTo(FixedCostConfig::class, 'org_id');
    }
}
