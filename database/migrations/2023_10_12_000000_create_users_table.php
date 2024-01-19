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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nick');
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('estatus_id');
            $table->foreign("persona_id")
                ->references('id')
                ->on("personas")
                ->onDelete('cascade');
            $table->foreign("estatus_id")
                ->references('id')
                ->on("estatus")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
