<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionInicialTable extends Migration
{
    public function up()
    {
        Schema::create('configuracion_inicial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_id')->unique();
            $table->decimal('saldo_inicial', 15, 2);
            $table->string('responsable', 100);
            $table->string('banco', 100)->nullable();
            $table->string('numero_cuenta', 50)->nullable();
            $table->enum('tipo_cuenta', ['caja', 'corriente', 'ahorro'])->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->timestamp('configurado_en')->useCurrent();

            $table->foreign('cuenta_id')->references('id')->on('cuentas')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('configuracion_inicial');
    }
}