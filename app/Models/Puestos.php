<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puestos extends Model
{
    // use HasFactory;
    protected $table = 'puesto';
    protected $connection = 'resguardo';
    protected $primaryKey='id_puesto';
    protected $fillable =[
        'nombre',
        'id_departamento',
        'estado'
    ];
    protected $guarded=[
    ];
    public function departamento()
    {
        return $this->belongsTo(Departamentos::class,'id_departamento','id_departamento');
    }
    /* protected $primaryKey = 'id_puesto';
    protected $connection='concentradora'; */
}