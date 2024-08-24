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
        Schema::create('OfertaCarrera', function (Blueprint $table) {
            $table->id('idOfertaCarrera');
            $table->unsignedBigInteger('idoferta'); 
            $table->unsignedBigInteger('idcarrera'); 
            $table->timestamps(); 

            $table->foreign('idoferta')
                ->references('idOfertaTrabajo')->on('oferta_trabajo')
                ->onDelete('cascade') 
                ->onUpdate('cascade'); 

            $table->foreign('idcarrera')
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
        Schema::dropIfExists('OfertaCarrera');
    }
};
