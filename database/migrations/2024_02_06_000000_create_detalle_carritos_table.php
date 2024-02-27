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
        Schema::create('detalle_carritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("producto_id");
            $table->unsignedBigInteger("carrito_id");
            $table->integer('cantidad');
            $table->decimal('subtotal', 8, 2)->default(0.00);
            $table->foreign("carrito_id")
                ->references('id')
                ->on("carritos")
                ->onDelete('cascade');
            $table->foreign("producto_id")
                ->references('id')
                ->on("productos")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_carritos');
    }
};
