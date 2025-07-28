<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanView extends Model
{
        protected $table = 'planes_vistas';

    // (Opcional) Si la tabla no tiene timestamps:
    public $timestamps = false;

    protected $fillable = [
        'plan_id',
        'vista_id'
    ];

    /**
     * Relación con el modelo Plan.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Relación con el modelo Vista.
     */
    public function vista(): BelongsTo
    {
        return $this->belongsTo(View::class, 'vista_id');
    }
}
