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
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('giro_id');
            $table->foreignId('sucursal_id')->index();
            $table->foreignId('user_asignado')->index();
            $table->string('nombre',100);
            $table->string('qr',255)->nullable()->comment('qr del carrito');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('giro_id')->references('id_giro')->on('giros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carritos');
    }
};
