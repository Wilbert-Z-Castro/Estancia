<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyecto_colab', function (Blueprint $table) {
            $table->id('idProyectoColab');
            $table->unsignedBigInteger('id_Egresado');
            $table->string('TituloProyecto', 45);
            $table->string('Descripcion', 45);
            $table->date('FechaPublicacion');
            $table->string('Imagen', 255);
            $table->timestamps();

            $table->foreign('id_Egresado')
                  ->references('idEgresado')->on('egresado')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_colab');
    }
};
