<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Activo extends Model
{
    //
    use SoftDeletes;

    //-----scopes-----
    public function scopeSearch(Builder $query,String $txt):void
    {
        $sucQuery = DB::connection('concentradora') // Define la conexión de la otra BD
        ->table('sucursales')
        ->select('id_sucursal', 'nombre')
        ->where('status', 1);
        $query->where(function($qw)use($txt){
            $qw->where('serie','LIKE','%'.$txt.'%')
            ->orWhere('activos.id','LIKE','%'.$txt.'%')
            ->orWhere('descripcion','LIKE','%'.$txt.'%')
            ->orWhere('num_equipo','LIKE','%'.$txt.'%')
            ->orWhereHas('tipos',function(Builder $tipos)use($txt){
                $tipos->where('nombre','LIKE','%'.$txt.'%');
            });
        });
        //sub join para buscar la sucursal
        $query->joinSub($sucQuery, 'sucursales', function ($join) {
            $join->on('activos.sucursal_id', '=', 'sucursales.id_sucursal');
        })
        ->orWhere('sucursales.nombre', 'LIKE', "%$txt%");
    }
    //----------------
    public function estadodepreciacion()
    {
        return $this->belongsTo(EstadosDepreciacion::class,'estatus_depreciacion', 'id_status');
    }
    public function tipos():BelongsTo
    {
        return $this->belongsTo(Tipo::class, 'tipo_id', 'id_tipo');
    }
    public function puestos():BelongsTo
    {
        return $this->belongsTo(Puesto::class, 'puesto_id', 'id_puesto');
    }
    public function sucursal():BelongsTo
    {
        return $this->belongsTo(Sucursales::class,'sucursal_id', 'id_sucursal');
    }
    public function empleado():BelongsTo
    {
        return $this->belongsTo(Empleado::class,'responsable_id', 'id_empleado');
    }
}
