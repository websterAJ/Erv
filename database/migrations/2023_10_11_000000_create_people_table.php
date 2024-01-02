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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->date('fecha_nacimiento');
            $table->string('genero');
            $table->string('cedula')->unique();
            $table->string('correo')->unique();
            $table->string('telefono');
            $table->string('direccion');
            $table->integer('tipo_personas_id');
            $table->integer('estatus_id');
            $table->integer('ascensos_id');
            $table->foreign("tipo_personas_id")
                ->references('id')
                ->on("tipo_personas")
                ->onDelete('cascade');
            $table->foreign("estatus_id")
                ->references('id')
                ->on("estatus")
                ->onDelete('cascade');
            $table->foreign("ascensos_id")
                ->references('id')
                ->on("ascensos")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
