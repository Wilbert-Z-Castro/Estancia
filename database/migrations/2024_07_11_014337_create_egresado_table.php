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
        Schema::create('egresado', function (Blueprint $table) {
            $table->id('idEgresado');
            $table->unsignedBigInteger('Id_user');
            $table->string('Matricula', 45);
            $table->unsignedBigInteger('Carrera');
            $table->date('AnioEgreso');
            $table->string('Direccion', 45)->nullable();
            $table->string('EmpresaActual', 45);
            $table->string('PuestoTrabajo', 45);
            $table->integer('AniosLaboral');
            $table->string('Adicional', 45)->nullable();
            $table->string('SectorEmpresaria', 45);
            $table->timestamps();
            $table->foreign('Id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('Carrera')
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
        Schema::dropIfExists('egresado');
    }
};
