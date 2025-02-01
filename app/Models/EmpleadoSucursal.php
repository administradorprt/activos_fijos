<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpleadoSucursal extends Model
{
    use HasFactory;
    protected $table='empleado_sucursal';
    protected $connection='concentradora';

    public function sucursal():BelongsTo
    {
        return $this->belongsTo(Sucursales::class,'id_sucursal');
    }
}
