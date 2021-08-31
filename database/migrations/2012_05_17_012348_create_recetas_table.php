<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('categoria_recetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('ingredientes'); // Text admet més de 255 caràcters
            $table->text('preparacion');
            $table->string('imagen');
            $table->foreignId('user_id')->references('id')->on('users')->comment('El usuario que crea la receta'); // Es guardarà la ID de l'usuari que crea la recepta
            $table->foreignId('categoria_id')->references('id')->on('categoria_recetas')->comment('La cateogría de la receta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_receta');
        Schema::dropIfExists('recetas');
    }
}
