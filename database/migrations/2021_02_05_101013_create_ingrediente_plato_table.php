<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientePlatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_plato', function (Blueprint $table) {
            $table->id();
            $table->double('cantidad', 8, 2);
        });
        Schema::table('ingrediente_plato', function (Blueprint $table) {
            $table->unsignedBigInteger('plato_id')->unsigned();
            $table->unsignedBigInteger('ingrediente_id')->unsigned();
            $table->foreign('plato_id')->references('id')->on('platos')->onDelete('cascade');
            $table->foreign('ingrediente_id')->references('id')->on('ingredientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingrediente_plato', function (Blueprint $table){
            $table->dropForeign('ingrediente_plato_plato_id_foreign');
            $table->dropForeign('ingrediente_plato_ingrediente_id_foreign');
        });
        Schema::dropIfExists('ingrediente_plato');
    }
}
