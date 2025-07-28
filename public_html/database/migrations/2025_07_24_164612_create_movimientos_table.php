<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('tipo', ['ingreso', 'egreso', 'transferencia']);
            $table->enum('subtipo', ['giro', 'deposito'])->nullable();
            $table->date('fecha');
            $table->bigInteger('monto');
            $table->text('descripcion')->nullable();
            $table->string('nro_dcto', 50);
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('cuenta_origen_id')->nullable();
            $table->unsignedBigInteger('cuenta_destino_id')->nullable();
            $table->string('proveedor', 100)->nullable();
            $table->string('rut_proveedor', 20)->nullable();
            $table->bigInteger('transferencia_id')->nullable();
            $table->timestamp('creado_en')->useCurrent();

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('restrict');
            $table->foreign('cuenta_origen_id')->references('id')->on('cuentas')->onDelete('restrict');
            $table->foreign('cuenta_destino_id')->references('id')->on('cuentas')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
}