<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('macromedidor_readings', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('frecuencia');
            $table->integer('lectura_anterior_extraccion');
            $table->integer('lectura_actual_extraccion');
            $table->integer('lectura_anterior_entrega');
            $table->integer('lectura_actual_entrega');
            $table->integer('extraccion_total');
            $table->integer('entrega_total');
            $table->integer('perdidas_total');
            $table->string('porcentaje_perdidas');
            $table->string('responsable');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('macromedidor_readings');
    }
};
