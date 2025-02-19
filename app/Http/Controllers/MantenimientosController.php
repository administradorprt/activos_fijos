<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\FrecuenciaMantenimiento;
use App\Models\ManteActivo;
use App\Models\Mantenimiento;
use App\Models\MantenimientoArchivo;
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
    public function create(ManteActivo $activo){
       return view('inventario.Mantenimientos.create',compact('activo'));
    }
    public function store(Request $request,ManteActivo $activo){
      //dd($activo);
      $request->validate([
        'tipo' =>'required',
        'proveedor' =>'required',
        'fecha' =>'required|date',
        'pdfs.*'=>'required|mimes:pdf',
        'foto.*' =>'mimes:jpg,bmp,png,jpegd',
      ]);
      //dd($activo->activo->tipos->giro->nombre);
      try {
        $mante=new Mantenimiento();
        $mante->mante_activo_id=$activo->id;
        $mante->activo_id=$activo->activo_id;
        $mante->tipo=$request->tipo;
        $mante->proveedor=$request->proveedor;
        $mante->fecha=$request->fecha;
        $mante->comentarios=$request->comentario;
        $mante->save();
        if($request->hasFile('pdfs')){
          foreach($request->file('pdfs') as $pdf){
            $path=$pdf->storeAs('/mantenimientos/pdfs/'.$activo->activo->tipos->giro->nombre.'/',$mante->id."_".time()."_".$pdf->getClientOriginalName(),'public');
            $archivo=new MantenimientoArchivo();
            $archivo->mantenimiento_id=$mante->id;
            $archivo->name=$pdf->getClientOriginalName();
            $archivo->extension=$pdf->getClientOriginalExtension();
            $archivo->type=$pdf->getMimeType();
            $archivo->path=$path;
            $archivo->save();
          }
        }
        if($request->hasFile('foto')){
          foreach($request->file('foto') as $foto){
            $path=$foto->storeAs('/mantenimientos/fotos/'.$activo->activo->tipos->giro->nombre.'/',$mante->id."_".time()."_".$foto->getClientOriginalName(),'public');
            $archivo=new MantenimientoArchivo();
            $archivo->mantenimiento_id=$mante->id;
            $archivo->name=$foto->getClientOriginalName();
            $archivo->extension=$foto->getClientOriginalExtension();
            $archivo->type=$foto->getMimeType();
            $archivo->path=$path;
            $archivo->save();
          }
        }
        $activo->ultimo_mante=$request->fecha;
        $activo->proximo_mante=Carbon::create($request->fecha)->addDays($activo->frecuencia->dias)->toDateString();
        $activo->update();
        return to_route('mante',$activo->activo->tipos->id_giro);
      } catch (Exception $e) {
        return back()->withInput()->withErrors(['error'=> 'Error al guardar el mantenimiento: '.$e->getMessage()]);
      }
    }
    public function updateProxMante(Request $request, ManteActivo $activo) {
      $request->validate(['fecha'=>'required']);
      $activo->proximo_mante=$request->fecha;
      $activo->update();
      return to_route('mante',$activo->activo->tipos->id_giro);
    }
    public function historico(Request $request,ManteActivo $activo) {
      //validamos que la ruta esté firmada
      if(!$request->hasValidSignature()){
        abort(401);
      }
      $activo->load([
        'lastMante'=>fn($last)=>$last->with('imagenes','pdfs'),
        'mantenimientos'=>fn($mantos)=>$mantos->select('id','mante_activo_id','fecha')->orderBy('fecha','DESC')
      ]);
      return view('inventario.Mantenimientos.historial',compact('activo'));
    }
    public function edit(Mantenimiento $mante){
      return view('inventario.Mantenimientos.edit',compact('mante'));
    }
    public function update(Request $request, Mantenimiento $mante){
      $request->validate([
        'tipo' =>'required',
        'proveedor' =>'required',
        'fecha' =>'required|date',
      ]);
      if($mante->imagenes->count()<=0){
        $request->validate([
          'foto' =>'required',
          'foto.*' =>'required|mimes:jpg,bmp,png,jpegd',
        ]);
      }
      if($mante->pdfs->count()<=0){
        $request->validate([
          'pdfs' =>'required',
          'pdfs.*'=>'required|mimes:pdf',
        ]);
      }
      try {
        $mante->tipo=$request->tipo;
        $mante->proveedor=$request->proveedor;
        $mante->fecha=$request->fecha;
        $mante->comentarios=$request->comentario;
        $mante->update();
        if($request->hasFile('pdfs')){
          foreach($request->file('pdfs') as $pdf){
            $path=$pdf->storeAs('/mantenimientos/pdfs/'.$mante->manteActivos->activo->tipos->giro->nombre.'/',$mante->id."_".time()."_".$pdf->getClientOriginalName(),'public');
            $archivo=new MantenimientoArchivo();
            $archivo->mantenimiento_id=$mante->id;
            $archivo->name=$pdf->getClientOriginalName();
            $archivo->extension=$pdf->getClientOriginalExtension();
            $archivo->type=$pdf->getMimeType();
            $archivo->path=$path;
            $archivo->save();
          }
        }
        if($request->hasFile('foto')){
          foreach($request->file('foto') as $foto){
            $path=$foto->storeAs('/mantenimientos/fotos/'.$mante->manteActivos->activo->tipos->giro->nombre.'/',$mante->id."_".time()."_".$foto->getClientOriginalName(),'public');
            $archivo=new MantenimientoArchivo();
            $archivo->mantenimiento_id=$mante->id;
            $archivo->name=$foto->getClientOriginalName();
            $archivo->extension=$foto->getClientOriginalExtension();
            $archivo->type=$foto->getMimeType();
            $archivo->path=$path;
            $archivo->save();
          }
        }
        $manteActivo=$mante->manteActivos;
        $manteActivo->ultimo_mante=$request->fecha;
        $manteActivo->proximo_mante=Carbon::create($request->fecha)->addDays($manteActivo->frecuencia->dias)->toDateString();
        $manteActivo->update();
        return to_route('mante',$manteActivo->activo->tipos->id_giro);
      } catch (Exception $e) {
        return back()->withInput()->withErrors(['error'=> 'Error al actualizar la información: '.$e->getMessage()]);

      }
    }
}
