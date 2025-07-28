<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConciliacionesTable extends Migration
{
    public function up()
    {
        Schema::create('conciliaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movimiento_id');
            $table->date('fecha_conciliacion');
            $table->enum('estado', ['conciliado', 'pendiente'])->default('pendiente');
            $table->text('comentario')->nullable();

            $table->foreign('movimiento_id')->references('id')->on('movimientos')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conciliaciones');
    }
}