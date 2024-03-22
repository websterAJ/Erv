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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referencia');
            $table->date('fecha');
            $table->unsignedBigInteger('pedido_id');
            $table->decimal('monto', 8, 2)->default(0.00);
            $table->unsignedBigInteger('estatus_id');
            $table->string('comprobante');
            $table->foreign("pedido_id")
                ->references('id')
                ->on("pedidos");
            $table->foreign("estatus_id")
                ->references('id')
                ->on("estatus");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
