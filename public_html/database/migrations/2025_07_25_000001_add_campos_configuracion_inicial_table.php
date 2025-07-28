<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposConfiguracionInicialTable extends Migration
{
    public function up()
    {
        // Las columnas ya existen, no hacer nada para evitar error de duplicado
        // Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
        //     $table->string('banco', 100)->nullable()->after('responsable');
        //     $table->string('numero_cuenta', 50)->nullable()->after('banco');
        //     $table->enum('tipo_cuenta', ['caja', 'corriente', 'ahorro'])->nullable()->after('numero_cuenta');
        //     $table->string('observaciones', 255)->nullable()->after('tipo_cuenta');
        // });
    }

    public function down()
    {
        // Las columnas ya existen, no hacer nada para evitar error de duplicado
        // Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
        //     $table->dropColumn(['banco', 'numero_cuenta', 'tipo_cuenta', 'observaciones']);
        // });
    }
}
