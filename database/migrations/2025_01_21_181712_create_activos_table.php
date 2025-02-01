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
        Schema::create('activos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('tipo_id');
            $table->foreignId('sucursal_id')->index();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('puesto_id')->nullable();
            $table->unsignedBigInteger('estatus_depreciacion')->default(1);
            $table->tinyInteger('estado')->default(1);
            $table->string('num_equipo',50)->nullable();
            $table->string('descripcion',1000)->nullable();
            $table->string('marca',50)->nullable();
            $table->string('serie',50)->nullable();
            $table->string('modelo',50)->nullable();
            $table->string('color',50)->nullable();
            $table->string('masivo',50)->nullable();
            $table->float('costo')->nullable();
            $table->string('nombre_provedor',50)->nullable();
            $table->string('num_factura',50)->nullable();
            $table->date('fecha_compra')->nullable();
            $table->string('xml',250)->nullable();
            $table->string('pdf',250)->nullable();
            $table->float('tasa_depreciacion')->nullable();
            $table->integer('vida_util')->nullable();
            $table->string('carta_responsiva',50)->nullable();
            $table->date('fecha_baja')->nullable();
            $table->string('motivo_baja',200)->nullable();
            $table->string('observaciones',250)->nullable();
            $table->string('garantia',255)->nullable();
            $table->string('id_proveedor',11)->nullable();
            $table->string('numero_de_pedido',11)->nullable();
            $table->date('fecha_vida_util_inicio')->nullable();
            $table->date('fecha_depreciacion_inicio')->nullable();
            $table->float('precio_venta')->nullable();
            $table->string('num_motor',50)->nullable()->comment('aplicado para equi transporte');
            $table->string('medida',100)->nullable()->comment('aplicado para herramientas');
            $table->string('qr',255)->nullable()->comment('qr del activo');
            $table->boolean('res_actualizado')->default(false);
            $table->boolean('cre_actualizado')->default(false);
            $table->boolean('ps_actualizado')->default(false);
            $table->boolean('depto_actualizado')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
