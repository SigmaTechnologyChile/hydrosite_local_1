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
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->after('member_id');
            $table->string('nombre')->nullable()->after('location_id');
            $table->string('telefono')->nullable()->after('nombre');
            $table->integer('order_by')->nullable()->after('telefono');
            $table->string('numero')->nullable()->after('order_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['location_id', 'nombre', 'telefono', 'order_by', 'numero']);
        });
    }
};
