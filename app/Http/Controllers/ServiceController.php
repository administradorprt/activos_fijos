<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Empleado;
use App\Models\ManteActivo;
use App\Models\Mantenimiento;
use App\Models\MantenimientoArchivo;
use App\Models\Tipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ServiceController extends Controller
{
  private $sucUsers;
  public function __construct()
  {
    if(Auth::user()){
      $this->sucUsers=Auth::user()->empleado->sucursales;
    }
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
      return Activo::where([['sucursal_id',$sucursal],['estado',1]])->whereIn('tipo_id',$tipos)->whereNotIn('id',$mantelist)->orderBy('descripcion','ASC')->orderBy('num_equipo','ASC')->select('id','descripcion','num_equipo')->get()->toJson();
    }catch(Exception $e){
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
  public function getQr(ManteActivo $id){
    try {
      if($id->activo->qr){
        return response()->json(['path'=>asset('storage/'.$id->activo->qr)]);
      }else{
        //generamos y guardamos el QR
        $qr=QrCode::size(250)->margin(2)->format('svg')->generate(URL::signedRoute('manteAct.show.public',$id->id));
        Storage::disk('public')->put('/mantenimientos/qrs/Qr_'.$id->id.'.svg',$qr);
        //Actualizamos el valor del qr del activo
        $id->activo->qr='/mantenimientos/qrs/Qr_'.$id->id.'.svg';
        $id->activo->save();
        return response()->json(['path'=>asset('storage/'.$id->activo->qr)]);
      }
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
  /**
   * Funcion para obtener toda la información de un mantenimiento
   * @return json respuesta con la información obtenida 
   */
  public function getManto(Mantenimiento $id){
    try {
      return response()->json($id->load('imagenes','pdfs'));
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
  public function delArchivosMante(Request $request,Mantenimiento $mante){
    try {
      MantenimientoArchivo::whereIn('id',$request->items)->delete();
      return response()->json(['selected'=>$request->items,'imgs'=>$mante->imagenes,'pdfs'=>$mante->pdfs]);
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);

    }
  }
  public function getEmpleadosBySucursal($sucursal){
    try {
      return Empleado::where([['id_sucursal_principal',$sucursal],['status',1]])->orderBy('nombres','ASC')->select('id_empleado','n_empleado','nombres','apellido_m','apellido_p','id_puesto','id_sucursal_principal',)->get()->toJson();
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
}
