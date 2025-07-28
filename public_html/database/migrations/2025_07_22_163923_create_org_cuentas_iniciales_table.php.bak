<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('org_cuentas_iniciales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->decimal('caja_general', 15, 2);
            $table->decimal('cta_cte_1', 15, 2);
            $table->decimal('cta_cte_2', 15, 2);
            $table->decimal('cta_ahorro', 15, 2);
            $table->date('fecha_inicial');
            $table->string('nro_movimiento');
            $table->timestamps();
            $table->foreign('org_id')->references('id')->on('orgs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_cuentas_iniciales');
    }
};
