<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\ManteActivo;
use App\Models\Tipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
  private $sucUsers;
  public function __construct()
  {
  $this->sucUsers=Auth::user()->empleado->sucursales;
  }
  public function getActivos(){
      return Activo::all()->toJson();
    }
  public function getActivosByType($giro){
    try{
      $tipos=Tipo::where('id_giro',$giro)->whereIn('sucursal_id',$this->sucUsers->pluck('id_sucursal'))->get()->pluck('id_tipo');
      return Activo::whereIn('tipo_id',$tipos)->get()->toJson();
    }catch(Exception $e){
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
  /**Funcion para obtener los activos que NO tienen mantenimiento según la sucursal y el el giro
   * @param  integer $sucursal id de la sucursal
   * @param integer $giro id del giro (mantenimento, herramientas...)
   */
  public function getActivosBySucursal($sucursal,$giro){
    try{
      $mantelist=ManteActivo::all()->pluck('activo_id');
      $tipos=Tipo::where([['id_giro',$giro],['sucursal_id',$sucursal]])->get()->pluck('id_tipo');
      return Activo::where([['sucursal_id',$sucursal],['estado',1]])->whereIn('tipo_id',$tipos)->whereNotIn('id',$mantelist)->get()->toJson();
    }catch(Exception $e){
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
}
