<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cajon extends Model
{
    use SoftDeletes;
    protected $table="cajones";

    public function activos():BelongsToMany
    {
        return $this->belongsToMany(Activo::class,'activos_carritos')->wherePivotNull('deleted_at');
    }
    public function carrito():BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }
}
