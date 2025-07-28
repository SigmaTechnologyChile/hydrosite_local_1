<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->decimal('saldo_inicial', 15, 2);
            $table->string('responsable', 100);
            $table->string('banco', 100)->nullable();
            $table->string('numero_cuenta', 50)->nullable();
            $table->string('tipo_cuenta', 50)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->unique(['org_id', 'cuenta_id']);
            $table->foreign('org_id')->references('id')->on('organizaciones')->onDelete('cascade');
            $table->foreign('cuenta_id')->references('id')->on('cuentas')->onDelete('restrict');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('configuracion_cuentas_iniciales');
    }
};
