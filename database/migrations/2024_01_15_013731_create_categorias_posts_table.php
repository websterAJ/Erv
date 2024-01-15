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
        Schema::create('categorias_posts', function (Blueprint $table) {
            $table->id();
            $table->integer("categoria_id");
            $table->integer("post_id");
            $table->foreign("categoria_id")
                ->references('id')
                ->on("categorias")
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
        Schema::dropIfExists('categorias_posts');
    }
};
