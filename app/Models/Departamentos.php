<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    //use HasFactory;
    protected $connection='concentradora';
    protected $primaryKey='id_departamento';
    protected $table = 'departamentos';
    /* protected $connection = 'resguardo';
    protected $table = 'departamento';
    protected $primaryKey='id_departamento';
    protected $fillable =[
        'nombre',
        'descripcion',
        'estado'
    ];
    protected $guarded=[
    ]; */
}
