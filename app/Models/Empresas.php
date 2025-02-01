<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empresa';

    protected $connection = 'concentradora';

    public function sucursales():HasMany
    {
        return $this->hasMany(Sucursales::class, 'id_empresa', 'id_empresa')->orderBy('nombre');
    }
}
