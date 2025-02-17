<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\FrecuenciaMantenimiento;
use App\Models\ManteActivo;
use App\Models\Tipo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManteActivosController extends Controller
{
    private $sucUsers,$user;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
      $this->user=Auth::user();
    }
    public function index($tipo){
        $tipos=Tipo::where('id_giro',$tipo)->whereIn('sucursal_id',$this->sucUsers->pluck('id_sucursal'))->get()->pluck('id_tipo');
        $activos=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->whereIn('sucursal_id',$this->sucUsers->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->get();
        $manteList=ManteActivo::where('status',1)->whereIn('activo_id',$activos->pluck('id'))->get();
        return view('inventario.MantenimientoActivos.index',compact('manteList','tipo'));
    }
    public function create($tipo){
      $sucursales=$this->sucUsers;
      $frecuencias=FrecuenciaMantenimiento::all();
       return view('inventario.MantenimientoActivos.create',compact('sucursales','frecuencias','tipo'));
    }
    public function store(Request $request){
      $request->validate([
        'sucursal_origen' =>'required',
        'activo' =>'required',
        'periodo' =>'required',
        'ubicacion' =>'required',
      ]);
      try {
        $fecha=Carbon::now()->toDateString();
        $mante=new ManteActivo();
        $mante->activo_id=$request->activo;
        $mante->frecuencia_id=$request->periodo;
        $mante->ultimo_mante=$fecha;
        $mante->proximo_mante=$fecha;
        $mante->user_created=$this->user->id;
        $mante->user_updated=$this->user->id;
        $mante->ubicacion=$request->ubicacion;
        $mante->status=true;
        $mante->save();
        $activo=Activo::find($request->activo);
        return to_route('mante',$activo->tipos->id_giro);
      } catch (Exception $e) {
        return back()->withInput()->withErrors(['error'=> 'Error al guardar el registro: '.$e->getMessage()]);
      }
    }
    public function show(ManteActivo $mante){
      $mante->load('activo','lastMante');
      return view('inventario.MantenimientoActivos.show',compact('mante'));
    }
    public function destroy($id){
      $mante=ManteActivo::findOrFail($id);
      $mante->status=false;
      $mante->save();
      return to_route('mante',5);
    }
    public function activar($id){
      $mante=ManteActivo::findOrFail($id);
      $mante->status=true;
      $mante->save();
      $mante->activo->estado=true;
      $mante->activo->update();
      return to_route('mante',5);
    }
}
