<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;
    protected $primaryKey='id_tipo';
    /* protected $table = 'tipos';
    public $timestamps=false;
    protected $fillable =[
        'nombre',
        'id_giro',
        'estado',
    ];
    public function Equipo()
    {
        return $this->hasMany(EquipoTransporte::class);
        // Si el id tienen diferentes nombres
        return $this->hasMany(EquipoTransporte::class, 'id_transporte_equipo', 'local_key');
    } */
}
