<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Empleado extends Model
{
    //use HasFactory;
    /* protected $connection = 'resguardo';
    protected $table = 'empleado';
    protected $primaryKey='id_empleado';
    protected $fillable =[
        'nombre',
        'id_puesto',
        'estado'
    ];
    public function users()
	{
    	return $this->hasMany(EquipoTransporte::class,'id_equipo_transporte');
	}

	public function departamento()
    {
        return $this->belongsTo(Departamentos::class,'area_destinada','id_departamento');
    }

    public function puesto()
    {
        return $this->belongsTo(Puestos::class,'id_puesto','id_puesto');
    } */
    protected $primaryKey='id_empleado';
    protected $connection='concentradora';
    public function emp_suc():BelongsTo
    {
        return $this->belongsTo(EmpleadoSucursal::class,'id_empleado','id_empleado');
    }
    public function sucursales():BelongsToMany
    {
        return $this->belongsToMany(Sucursales::class,'empleado_sucursal','id_empleado','id_sucursal','id_empleado');
    }
    public function puesto():BelongsTo
    {
        return $this->belongsTo(Puesto::class,'id_puesto');
    }
    public function mainSucursal():BelongsTo
    {
        return $this->belongsTo(Sucursales::class,'id_sucursal_principal','id_sucursal');
    }
}