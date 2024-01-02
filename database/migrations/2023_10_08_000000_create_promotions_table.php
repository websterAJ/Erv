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
        Schema::create('ascensos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->boolean("activo");
            $table->integer("tipo_ascensos_id");
            $table->foreign("tipo_ascensos_id")
                ->references('id')
                ->on("tipo_ascensos")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ascensos');
    }
};
