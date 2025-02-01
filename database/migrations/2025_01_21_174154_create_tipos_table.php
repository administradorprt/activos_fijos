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
        Schema::create('tipos', function (Blueprint $table) {
            $table->id('id_tipo');
            $table->unsignedBigInteger('id_giro');
            $table->foreignId('sucursal_id')->index();
            $table->string('nombre',250);
            $table->tinyInteger('estado');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('id_giro')->references('id_giro')->on('giros')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};
