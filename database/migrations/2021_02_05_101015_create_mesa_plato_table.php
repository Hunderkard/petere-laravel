<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesaPlatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesa_plato', function (Blueprint $table) {
            $table->id();
            $table->double('precio', 5, 2);
            $table->string('plato_nombre');
            $table->timestamps();
        });


        Schema::table('mesa_plato', function (Blueprint $table) {            
            $table->unsignedBigInteger('plato_id')->unsigned();
            $table->unsignedBigInteger('mesa_id')->unsigned();
            $table->foreign('plato_id')->references('id')->on('platos');
            $table->foreign('mesa_id')->references('id')->on('mesas');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mesa_plato', function (Blueprint $table){
            $table->dropForeign('mesa_plato_plato_id_foreign');
            $table->dropForeign('mesa_plato_mesa_id_foreign');
        });
        Schema::dropIfExists('mesa_plato');
    }
}
