<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_genero')->references('id')->on('generos');
            $table->foreignId('fk_proveedor')->references('id')->on('proveedors');
            $table->decimal('precio',10,2);
            $table->string('nombre');
            $table->string('archivo_demo');
            $table->string('archivo_descarga');
            $table->integer('estado');
            $table->integer('es_archivo');
            $table->string('caratula')->nullable();
            $table->dateTime('fecha');
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
        Schema::dropIfExists('productos');
    }
};
