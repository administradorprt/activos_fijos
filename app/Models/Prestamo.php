<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    use SoftDeletes;
    
    public function activo():BelongsTo
    {
        return $this->belongsTo(Activo::class);
    }
    public function usuario():BelongsTo
    {
        return $this->belongsTo(Empleado::class,'user_id','id_empleado');
    }
}
