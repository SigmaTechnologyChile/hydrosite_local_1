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
        Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->unsignedBigInteger('org_id')->after('id');
            // Si tienes la tabla de organizaciones, puedes agregar la foreign key:
            // $table->foreign('org_id')->references('id')->on('organizaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->dropColumn('org_id');
        });
    }
};
