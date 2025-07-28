<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            $table->unsignedBigInteger('org_id')->nullable()->after('id');
            // Si tienes tabla organizaciones, puedes agregar la FK:
            // $table->foreign('org_id')->references('id')->on('organizaciones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // $table->dropForeign(['org_id']); // Descomenta si agregaste la FK
            $table->dropColumn('org_id');
        });
    }
};
