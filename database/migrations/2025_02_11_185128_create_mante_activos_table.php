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
        Schema::create('mante_activos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activo_id');
            $table->unsignedBigInteger('frecuencia_id');
            $table->date('ultimo_mante')->nullable();
            $table->date('proximo_mante');
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('activo_id')->references('id')->on('activos')->onDelete('cascade');
            $table->foreign('frecuencia_id')->references('id')->on('frecuencia_mantenimientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mante_activos');
    }
};
