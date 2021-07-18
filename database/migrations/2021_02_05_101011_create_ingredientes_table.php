<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CreateIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            $table->double('valor', 8, 6);         /* Pagado por x cantidad */
            $table->double('stock', 8, 4);
            $table->string('unidad_de_medida');
           $table->string('foto');
            $table->text('observaciones')->nullable();
            // $table->date('fecha_compra')->nullable();
            // $table->date('fecha_caducidad')->nullable();

        });

        Schema::table('ingredientes', function (Blueprint $table){
            $table->unsignedBigInteger('proveedor_id')->unsigned();
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredientes', function (Blueprint $table){
            $table->dropForeign('ingredientes_proveedor_id_foreign');
        });

        Schema::dropIfExists('ingredientes');
    }
}
