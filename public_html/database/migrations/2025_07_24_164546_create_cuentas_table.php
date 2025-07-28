<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasTable extends Migration
{
    public function up()
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->enum('tipo', ['caja', 'corriente', 'ahorro']);
            $table->decimal('saldo_actual', 15, 2)->default(0.00);
            $table->string('banco', 100)->nullable();
            $table->string('numero_cuenta', 50)->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cuentas');
    }
}