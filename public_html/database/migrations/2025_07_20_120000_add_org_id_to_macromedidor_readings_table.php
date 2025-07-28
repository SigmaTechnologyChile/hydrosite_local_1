<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgIdToMacromedidorReadingsTable extends Migration
{
    public function up()
    {
        Schema::table('macromedidor_readings', function (Blueprint $table) {
            $table->unsignedBigInteger('org_id')->after('id')->default(1);
            // Si tienes tabla de organizaciones, puedes agregar foreign key:
            // $table->foreign('org_id')->references('id')->on('organizations');
        });
    }

    public function down()
    {
        Schema::table('macromedidor_readings', function (Blueprint $table) {
            $table->dropColumn('org_id');
        });
    }
}
