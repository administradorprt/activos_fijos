<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puesto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_puesto';
    protected $connection='concentradora';
    public function empleados():HasMany
    {
        return $this->hasMany(Empleado::class,'id_puesto','id_puesto');
    }
    public function departamento():BelongsToMany
    {
        return $this->belongsToMany(Departamentos::class,'departamento_puesto','id_puesto','id_departamento');
    }
}
