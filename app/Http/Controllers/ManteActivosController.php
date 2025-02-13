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
    private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    public function index($tipo){
        $tipos=Tipo::where('id_giro',$tipo)->whereIn('sucursal_id',$this->sucUsers->pluck('id_sucursal'))->get()->pluck('id_tipo');
        $activos=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->whereIn('sucursal_id',$this->sucUsers->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->get();
        $manteList=ManteActivo::whereIn('activo_id',$activos->pluck('id'))->get();
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
      ]);
      try {
        $fecha=Carbon::now()->toDateString();
        $mante=new ManteActivo();
        $mante->activo_id=$request->activo;
        $mante->frecuencia_id=$request->periodo;
        $mante->ultimo_mante=$fecha;
        $mante->proximo_mante=$fecha;
        $mante->status=true;
        $mante->save();
        return to_route('mante',5);
      } catch (Exception $e) {
        return back()->with('error', 'Error al guardar el mantenimiento: '.$e->getMessage());
      }
    }
    public function show(ManteActivo $mante){
      $mante->load('activo');
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
      return to_route('mante',5);
    }
}
