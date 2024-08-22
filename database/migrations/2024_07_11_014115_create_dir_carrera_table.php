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
        Schema::create('dir_carrera', function (Blueprint $table) {
            $table->id('idDirCarrera');
            $table->string('Descripcion', 120);
            $table->integer('AnioInstitucion');
            $table->unsignedBigInteger('id_userDir')->nullable();
            $table->date('FechaAsignacion')->nullable();
            $table->timestamps();

            $table->foreign('id_userDir')
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
        Schema::dropIfExists('dir_carrera');
    }
};
