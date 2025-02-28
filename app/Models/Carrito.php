<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrito extends Model
{
    use SoftDeletes;

    public function sucursal():BelongsTo
    {
        return $this->belongsTo(Sucursales::class,'sucursal_id','id_sucursal');
    }
    public function asignado():BelongsTo
    {
        return $this->belongsTo(Empleado::class,'user_asignado','id_empleado');
    }
    public function cajones():HasMany
    {
        return $this->hasMany(Cajon::class);
    }
}
