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
        Schema::create('subzonas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->boolean("activo");
            $table->integer("zonas_id");
            $table->foreign("zonas_id")
                ->references('id')
                ->on("zonas")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subzonas');
    }
};
