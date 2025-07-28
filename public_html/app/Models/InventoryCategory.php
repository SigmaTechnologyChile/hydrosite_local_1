<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $table = 'inventories_categories';
    
    protected $fillable = [
        'name', 'active', 'org_id',
    ];
    
    public $timestamps = false;

    public function inventaries()
    {
        return $this->hasMany(Inventary::class);
    }
}
