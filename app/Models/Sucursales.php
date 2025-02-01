<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Sucursales extends Model
{
    use HasFactory;
    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';
    protected $connection = 'concentradora';

    //-----------scopes-----
    //----------------------
    public function suc_deptos():HasMany
    {
        return $this->hasMany(SucursalDeptos::class,'id_sucursal')->where('status',1);
    }
    public function emp_suc():HasMany
    {
        return $this->hasMany(EmpleadoSucursal::class,'id_sucursal');
    }
    public function empresa():BelongsTo
    {
        return $this->belongsTo(Empresas::class,'id_empresa','id_empresa');
    }
    public function empleados():BelongsToMany
    {
        return $this->belongsToMany(Empleado::class,'empleado_sucursal','id_sucursal','id_empleado','id_sucursal');
    }
    public function deptos():BelongsToMany
    {
        return $this->belongsToMany(Departamento::class,'suc_dep','id_sucursal','id_departamento','id_sucursal','id_departamento');
    }
}
