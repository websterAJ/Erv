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
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("usuario_id");
            $table->unsignedBigInteger("status_id");
            $table->date('fecha');
            $table->decimal('total', 8, 2)->default(0.00);
            $table->double('iva');
            $table->foreign("usuario_id")
            ->references('id')
            ->on("usuarios")
            ->onDelete('cascade');
            $table->foreign("status_id")
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
        Schema::dropIfExists('carritos');
    }
};
