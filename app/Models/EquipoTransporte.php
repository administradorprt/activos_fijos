<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoTransporte extends Model
{
    //use HasFactory;
    protected $table = 'equipo_transporte';
    protected $primaryKey='id_equipo_transporte';

    public $timestamps=false;

    protected $fillable =[
        'estado',
        //'num_equipo',
        'descripcion',
        'marca',
        'vin',
        'modelo',
        'num_motor',
        'color',
        'tipo',
        //'moi',
        'costo',
        'nombre_provedor',
        'num_factura',
        'fecha_compra',
        'xml',
        'pdf',
        'foto',
        'tasa_depreciacion',
        'vida_util',
        'area_destinada',
        'puesto',
        'nombre_responsable',
        'carta_responsiva',
        'fecha_baja',
        'motivo_baja',
        'observaciones',
        'garantia',
        'created_user',
    ];
    protected $guarded=[
    ];
    public function tipos()
    {
        return $this->belongsTo(Tipo::class,'tipo', 'id_tipo');
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class,'nombre_responsable','id_empleado');
    }
    public function area()
    {
        return $this->belongsTo(Departamentos::class,'area_destinada','id_departamento');
    }
    public function puestos()
    {
        return $this->belongsTo(Puestos::class,'puesto','id_puesto');
    }
	public function estadodepreciacion()
    {
        return $this->belongsTo(EstadosDepreciacion::class,'estatus_depreciacion', 'id_status');
    }
}
