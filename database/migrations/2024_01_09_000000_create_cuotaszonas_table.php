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
        Schema::create('cuotaszonas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('zona_id');
            $table->integer('estatus_id');
            $table->decimal('monto', 8, 2)->default(0.00);
            $table->foreign("zona_id")
                ->references('id')
                ->on("zonas");
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
        Schema::dropIfExists('cuotaszonas');
    }
};
