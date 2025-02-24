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
        Schema::create('activos_carritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cajon_id');
            $table->unsignedBigInteger('activo_id');
            $table->boolean('status');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cajon_id')->references('id')->on('cajones')->onDelete('cascade');
            $table->foreign('activo_id')->references('id')->on('activos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos_carritos');
    }
};
