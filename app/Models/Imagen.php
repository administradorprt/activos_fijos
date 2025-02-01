<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    //use HasFactory;
    protected $table = 'imagenes';
    protected $primaryKey='id_imagen';
    public $timestamps=false;
    protected $fillable =[
        'id_area',
        'id_equipo',
        'imagen',
        'estado',
    ];
    protected $guarded=[
    ];
}