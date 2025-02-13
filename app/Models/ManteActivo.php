<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManteActivo extends Model
{
    use SoftDeletes;

    //---scopes---

    //------------
    public function activo():BelongsTo
    {
        return $this->belongsTo(Activo::class);
    }
    public function frecuencia():BelongsTo
    {
        return $this->belongsTo(FrecuenciaMantenimiento::class);
    }
    public function mantenimientos():HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }
    //obtener último mantenimiento por relación
    public function lastMante():HasOne
    {
        return $this->hasOne(Mantenimiento::class)->latest('id');
    }
}
