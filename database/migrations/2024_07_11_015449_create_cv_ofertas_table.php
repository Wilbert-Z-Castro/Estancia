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
        Schema::create('cv_ofertas', function (Blueprint $table) {
            $table->id('idCVOferta');
            $table->unsignedBigInteger('Id_oferta');
            $table->string('Mensaje', 45)->nullable();
            $table->string('Cv', 45);
            $table->unsignedBigInteger('id_egresado');
            $table->timestamps();

            $table->foreign('Id_oferta')
                  ->references('idOfertaTrabajo')->on('oferta_trabajo')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_egresado')
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
        Schema::dropIfExists('cv_ofertas');
    }
};
