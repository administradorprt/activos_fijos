<?php

namespace App\Http\Controllers;

use App\Exports\PrestamosExport;
use App\Models\Prestamo;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PrestamosController extends Controller
{
    private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    public function index($tipo){
        $prestamos=Prestamo::where('fecha_devuelto',null)->whereHas('activo',function(Builder $activo)use($tipo){
            $activo->whereIn('sucursal_id', $this->sucUsers->pluck('id_sucursal'))->whereHas('tipos',function(Builder $tp)use($tipo){
                $tp->whereIn('sucursal_id', $this->sucUsers->pluck('id_sucursal'))->where('id_giro', $tipo);
            });
        })->get();
        return view('inventario.Prestamos.index',compact('tipo', 'prestamos'));
    }
    public function create($tipo){
        $sucursales=$this->sucUsers;
        return view('inventario.Prestamos.create',compact('sucursales','tipo'));
    }
    public function store(Request $request,$tipo){
        $request->validate([
            'activo'=>'required',
           'user'=>'required',
           'fecha'=>'required|date',
        ]);
        try {
            $pres=new Prestamo();
            $pres->activo_id=$request->activo;
            $pres->user_id=$request->user;
            $pres->fecha=$request->fecha;
            $pres->save();
            return redirect()->route('prestamos',$tipo);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error'=> 'Error al guardar el mantenimiento: '.$e->getMessage()]);
        }
    }
    public function devolucion(Request $request,Prestamo $prestamo){
        $request->validate([
            'fecha_fin'=>'required|date',
            'comentario'=>'required',
        ]);
        try {
            $prestamo->fecha_devuelto=$request->fecha_fin;
            $prestamo->detalles=$request->comentario;
            $prestamo->update();
            return redirect()->route('prestamos',$prestamo->activo->tipos->id_giro);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error'=> 'Error al guardar el mantenimiento: '.$e->getMessage()]);

        }
    }
    public function genReporte(Request $request,$tipo){
        $request->validate([
            'fecha_in'=>'required|date|before_or_equal:fecha_fin',
            'fecha_fin'=>'required|date|after_or_equal:fecha_in',
        ]);
        return Excel::download(new PrestamosExport($tipo,$request->fecha_in,$request->fecha_fin),'reporte de prestamos general.xlsx');
    }
}
