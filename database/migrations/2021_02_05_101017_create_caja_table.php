<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->id();
            $table->double('cobro', 6, 2);
            $table->time('hora');
            $table->date('dia');
            $table->timestamps();
        });


        Schema::table('caja', function (Blueprint $table) {        
            $table->unsignedBigInteger('mesa_id')->unsigned();
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
        Schema::table('caja', function (Blueprint $table) {
            $table->dropForeign('caja_mesa_id_foreign');
        });
        Schema::dropIfExists('caja');
    }
}
