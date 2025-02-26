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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activo_id');
            $table->foreignId('user_id')->index();
            $table->dateTime('fecha');
            $table->dateTime('fecha_devuelto')->nullable();
            $table->string('detalles',255)->nullable();
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
        Schema::dropIfExists('prestamos');
    }
};
