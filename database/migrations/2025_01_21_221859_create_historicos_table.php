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
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activo_id');
            $table->foreignId('user_asignado')->index();
            $table->foreignId('sucursal_id')->index();
            $table->date('fecha_asignacion');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('activo_id')->references('id')->on('activos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
