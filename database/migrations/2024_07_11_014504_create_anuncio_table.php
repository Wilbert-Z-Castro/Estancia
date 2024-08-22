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
        Schema::create('anuncio', function (Blueprint $table) {
            $table->id('idAnuncio');
            $table->string('Titulo', 45);
            $table->unsignedBigInteger('Categoria');
            $table->string('Contenido', 255);
            $table->string('Imagen', 255)->nullable();
            $table->unsignedBigInteger('Id_userCreado')->nullable();
            $table->timestamps();

            $table->foreign('Categoria')
                  ->references('idCatAnuncio')->on('cat_anuncio')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('Id_userCreado')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncio');
    }
};
