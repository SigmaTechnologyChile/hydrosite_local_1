<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriaCuentasTable extends Migration
{
    public function up()
    {
        Schema::create('auditoria_cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_id');
            $table->decimal('saldo_anterior', 15, 2);
            $table->decimal('saldo_nuevo', 15, 2);
            $table->decimal('cambio', 15, 2);
            $table->string('usuario', 100);
            $table->timestamp('modificado_en')->useCurrent();
            $table->foreign('cuenta_id')->references('id')->on('cuentas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('auditoria_cuentas');
    }
}