<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class Tipo extends Model
{
    use HasFactory;
    protected $primaryKey='id_tipo';
    //-----scopes-----
    public function scopeSearch(Builder $query,$txt):void
    {
        $sucQuery = DB::connection('concentradora') // Define la conexión de la otra BD
        ->table('sucursales')
        ->select('id_sucursal', 'nombre as suc_nombre')
        ->where('status', 1);

        $query->where('nombre','LIKE',"%$txt%")
        ->orWhere('id_tipo','LIKE',"%$txt%")
        ->orWhereHas('giro',function(Builder $giro)use($txt){
            $giro->where('nombre','LIKE',"%$txt%");
        });
        $query->joinSub($sucQuery,'sucursales',function(JoinClause $join){
            $join->on('tipos.sucursal_id', '=', 'sucursales.id_sucursal');
        })
        ->orWhere('suc_nombre', 'LIKE', "%$txt%");
    }
    //---------------
    //-----relationships-----
    public function giro():BelongsTo
    {
        return $this->belongsTo(Giro::class,'id_giro','id_giro');
    }
    public function sucursal():BelongsTo
    {
        return $this->belongsTo(Sucursales::class,'sucursal_id','id_sucursal');
    }
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
