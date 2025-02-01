<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activo extends Model
{
    //
    use SoftDeletes;
    public function estadodepreciacion()
    {
        return $this->belongsTo(EstadosDepreciacion::class,'estatus_depreciacion', 'id_status');
    }
    public function tipos():BelongsTo
    {
        return $this->belongsTo(Tipo::class, 'tipo_id', 'id_tipo');
    }
    public function puestos():BelongsTo
    {
        return $this->belongsTo(Puesto::class, 'puesto_id', 'id_puesto');
    }
}
