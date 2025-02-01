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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activo_id');
            $table->string('proveedor',100);
            $table->string('comentarios',255)->nullable();
            $table->string('pdf',255);
            $table->string('xml',255);
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
        Schema::dropIfExists('mantenimientos');
    }
};
