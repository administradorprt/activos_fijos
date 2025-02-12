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
            $table->unsignedBigInteger('mante_activo_id');
            $table->unsignedBigInteger('activo_id')->nullable();
            $table->tinyInteger('tipo',unsigned:true);
            $table->string('proveedor',100)->nullable();
            $table->date('fecha');
            $table->string('comentarios',255)->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('mante_activo_id')->references('id')->on('mante_activos')->onDelete('cascade')->onUpdate('cascade');
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
