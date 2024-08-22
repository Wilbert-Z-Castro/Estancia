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
        Schema::create('cat_anuncio', function (Blueprint $table) {
            $table->id('idCatAnuncio');
            $table->string('Nombre', 45);
            $table->string('Descripcion', 45);
            $table->string('Color', 45);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_anuncio');
    }
};
