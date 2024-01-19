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
        Schema::create('comentarios_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("comentario_id");
            $table->unsignedBigInteger("post_id");
            $table->foreign("comentario_id")
                ->references('id')
                ->on("comentarios")
                ->onDelete('cascade');
            $table->foreign("post_id")
                ->references('id')
                ->on("posts")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios_posts');
    }
};
