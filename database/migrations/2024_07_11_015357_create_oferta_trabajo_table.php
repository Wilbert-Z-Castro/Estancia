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
        Schema::create('oferta_trabajo', function (Blueprint $table) {
            $table->id('idOfertaTrabajo');
            $table->string('TituloOferta', 45);
            $table->string('Descripcion', 45);
            $table->string('Requisitos', 45);
            $table->unsignedBigInteger('CarreraDir');
            $table->string('Empresa', 45)->nullable();
            $table->string('Ubicacion', 45);
            $table->date('FechaOferta');
            $table->string('Imagen', 200)->nullable();
            $table->string('SectorEmpre', 45);
            $table->timestamps();

            $table->foreign('CarreraDir')
                  ->references('idCarrera')->on('carrera')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_trabajo');
    }
};
