<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla en la base de datos
    protected $table = 'inventories_categories';
    
    protected $fillable = [
        'name', 'active', 'org_id',
    ];
    
    // Evitar la creación, actualización y eliminación desde el modelo
    public $timestamps = false;

    public function inventaries()
    {
        return $this->hasMany(Inventary::class); // Relación de "tiene muchos"
    }
   
}
