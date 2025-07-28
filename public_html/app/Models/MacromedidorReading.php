<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MacromedidorReading extends Model
{
    protected $fillable = [
        'org_id', 'fecha', 'frecuencia', 'lectura_anterior_extraccion', 'lectura_actual_extraccion',
        'lectura_anterior_entrega', 'lectura_actual_entrega', 'extraccion_total', 'entrega_total',
        'perdidas_total', 'porcentaje_perdidas', 'responsable', 'observaciones'
    ];
}
