<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('foto');
            $table->double('precio', 5, 2);
            $table->text('observaciones')->nullable();
            //pl Ponerlos en una tabla a parte.
            $table->enum('tipo', ['ENTRANTE', 'HAMBURGUESA', 'PASTA', 'PIZZA', 'BEBIDA_CALIENTE', 'REFRESCO']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platos');
    }
}
