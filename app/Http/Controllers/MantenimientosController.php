<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\FrecuenciaMantenimiento;
use App\Models\ManteActivo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MantenimientosController extends Controller
{
    private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    /* public function index($tipo){
        return view('inventario.Mantenimientos.index');
    } */
    public function create($tipo){
      $sucursales=$this->sucUsers;
      $frecuencias=FrecuenciaMantenimiento::all();
       return view('inventario.Mantenimientos.create',compact('sucursales','frecuencias'));
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
}
