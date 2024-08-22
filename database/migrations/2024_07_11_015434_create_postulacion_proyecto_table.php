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
        Schema::create('postulacion_proyecto', function (Blueprint $table) {
            $table->id('idPostulacionProyecto');
            $table->unsignedBigInteger('Id_proyecto');
            $table->string('Mensaje', 45)->nullable();
            $table->string('Cv', 45);
            $table->unsignedBigInteger('id_user');
            $table->string('Estado', 45);
            $table->timestamps();

            $table->foreign('Id_proyecto')
                  ->references('idProyectoColab')->on('proyecto_colab')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacion_proyecto');
    }
};
