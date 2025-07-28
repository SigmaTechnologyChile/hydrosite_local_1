<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('grupo', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}