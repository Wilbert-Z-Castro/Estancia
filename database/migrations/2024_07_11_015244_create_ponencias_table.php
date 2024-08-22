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
        Schema::create('ponencias', function (Blueprint $table) {
            $table->id('idPonencias');
            $table->string('TituloPonencia', 45);
            $table->string('DescripcionPonencia', 45);
            $table->date('Fecha');
            $table->string('Lugar', 200);
            $table->unsignedBigInteger('Id_DirCarrera');
            $table->timestamps();
            $table->foreign('Id_DirCarrera')
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
        Schema::dropIfExists('ponencias');
    }
};
