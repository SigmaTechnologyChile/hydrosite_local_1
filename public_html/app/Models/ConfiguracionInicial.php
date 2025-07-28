<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionInicial extends Model
{
    use HasFactory;

    protected $table = 'configuracion_cuentas_iniciales';

    protected $fillable = [
        'org_id',
        'cuenta_id',
        'saldo_inicial',
        'responsable',
        'banco_id',
        'numero_cuenta',
        'tipo_cuenta',
    ];

    public $timestamps = false;

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }
}