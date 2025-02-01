<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosDepreciacion extends Model
{
    //use HasFactory;
    protected $table = 'estados_depreciacion';
    protected $primaryKey='id_status';
    public $timestamps=false;
    protected $fillable =[
       'nombre',
       'estado',
    ];
}
