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
        Schema::create('carrera', function (Blueprint $table) {
            $table->id('idCarrera');
            $table->string('NombreCarrera', 45);
            $table->string('Descripcion', 45);
            $table->unsignedBigInteger('id_DirCarrera');
            $table->string('PlanEstudios', 200);
            $table->string('Organigrama', 200);
            $table->string('UbicacionOficinas', 45)->nullable();
            $table->timestamps();
            $table->foreign('id_DirCarrera')
                  ->references('idDirCarrera')->on('dir_carrera')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrera');
    }
};
