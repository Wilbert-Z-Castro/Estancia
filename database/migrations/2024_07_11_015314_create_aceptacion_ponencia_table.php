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
        Schema::create('aceptacion_ponencia', function (Blueprint $table) {
            $table->id('idAceptacionPonencia');
            $table->string('Estado', 45);
            $table->unsignedBigInteger('Id_Ponencia');
            $table->unsignedBigInteger('id_Egresado');
            $table->string('Mensaje', 100);
            $table->string('Archivo', 255);
            $table->timestamps();

            $table->foreign('Id_Ponencia')
                  ->references('idPonencias')->on('ponencias')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

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
        Schema::dropIfExists('aceptacion_ponencia');
    }
};
