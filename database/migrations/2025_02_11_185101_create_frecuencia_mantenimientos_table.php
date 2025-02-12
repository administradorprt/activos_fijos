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
        Schema::create('frecuencia_mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->integer('dias');
            $table->boolean('manual')->default(false)->comment('columna para indicar si se activa campo de fecha');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frecuencia_mantenimientos');
    }
};
