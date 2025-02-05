<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HerramientasRequest;
use App\Models\Herramientas;
use App\Models\Empleado;
use App\Models\Departamentos;
use App\Models\Puestos;
use App\Models\Tipo;
use App\Models\Imagen;
use App\Models\EstadosDepreciacion;
use DB;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Validator;
use App\Exports\HerramientasExport;
use App\Models\Activo;
use App\Models\Puesto;
use App\Models\Sucursales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HerramientasController extends Controller
{
    public function index(Request $request)
    {
    	if($request)
    	{
    		$querry=trim($request->get('searchText'));
            $sucursales=Auth::user()->empleado->sucursales;
            $tipos=Tipo::where('id_giro',5)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get()->pluck('id_tipo');
            if(auth()->user()->role_id<3){
				/* $Herramientas=Activo::leftJoin('tipos', 'activos.tipo_id', '=', 'tipos.id_tipo')->
				Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('activos.id','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->whereIn('activos.sucursal_id',$sucursales)->whereIn('id_tipo',$tipos)
                ->select("activos.id","num_equipo","activos.estado","descripcion","marca","serie as codigo","modelo","tipos.nombre","created_user")
				->paginate(10); */
                $Herramientas=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->search($querry)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->orderBy('id','ASC')->paginate(10);
			}else{
                $Herramientas=Activo::leftJoin('tipos', 'activos.tipo_id', '=', 'tipos.id_tipo')->
                where('created_user', '=', auth()->user()->id)
				->Where(function ($query) use($querry){
					$query->where('codigo','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_herramienta','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_herramienta","num_equipo","herramientas.estado","descripcion","marca","codigo","modelo","tipos.nombre","created_user")
				->paginate(10);
            }
    		return view('inventario.Herramientas.index',["Herramientas"=>$Herramientas,"searchText"=>$querry,'sucursales'=>$sucursales]);
    	}
    }
    public function create()
    {
        $sucursales=Auth::user()->empleado->sucursales;
        $Departamento=Departamentos::where('status','=','1')->orderBy('nombre')->get();
        //$Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
        $Puesto=Puesto::join('departamento_puesto as dp','puestos.id_puesto','=','dp.id_puesto')->select('puestos.*','dp.id_departamento')->get();
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','5')->get();
        $depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
    	return view("inventario.Herramientas.create",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);
    }
    public function store(HerramientasRequest $request)
    {					  
    	$Herramientas= new Activo();
    	$Herramientas->estado='1';
    	$Herramientas->descripcion=$request->get('descripcion');
    	$Herramientas->marca=$request->get('marca');
    	$Herramientas->serie=$request->get('codigo');
    	$Herramientas->modelo=$request->get('modelo');
    	$Herramientas->medida=$request->get('medida');
    	$Herramientas->color=$request->get('color');
    	$Herramientas->tipo_id=$request->get('tipo');
        $Herramientas->costo=$request->get('costo');
    	$Herramientas->nombre_provedor=$request->get('nombre_provedor');
        $Herramientas->id_proveedor=$request->get('id_proveedor');
		$Herramientas->numero_de_pedido=$request->get('numero_de_pedido');
    	$Herramientas->num_factura=$request->get('num_factura');
    	$Herramientas->fecha_compra=$request->get('fecha_compra');
        $Herramientas->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$Herramientas->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	$Herramientas->tasa_depreciacion=$request->get('tasa_depreciacion');
    	$Herramientas->vida_util=$request->get('vida_util');
    	$Herramientas->departamento_id=$request->get('area_destinada');
    	$Herramientas->puesto_id=$request->get('puesto');
    	$Herramientas->responsable_id=$request->get('nombre_responsable');
       	$Herramientas->observaciones=$request->get('observaciones');
    	$Herramientas->garantia=$request->get('garantia');
        $Herramientas->created_user=auth()->user()->id;
        $Herramientas->sucursal_id=$request->get('sucursal_origen');
    	$Herramientas->save();
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		//$file->move(public_path().'/archivos/inventario/Herramientas/xml/',$Herramientas->id."-".$file->getClientOriginalName());
    		$file->storeAs('/archivos/inventario/Herramientas/xml/',$Herramientas->id."-".$file->getClientOriginalName(),'public');
    		$Herramientas->xml=$Herramientas->id."-".$file->getClientOriginalName();
    	}  	
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		//$file->move(public_path().'/archivos/inventario/Herramientas/pdf/',$Herramientas->id_equipo_herramienta."-".$file->getClientOriginalName());
    		$file->storeAs('/archivos/inventario/Herramientas/pdf/',$Herramientas->id."-".$file->getClientOriginalName(),'public');
    		$Herramientas->pdf=$Herramientas->id."-".$file->getClientOriginalName();
    	}
        $id=$Herramientas->id;
    	if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/Herramientas/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/Herramientas/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='5';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
        $tipos=Tipo::where('id_giro',5)->where('sucursal_id',$request->get('sucursal_origen'))->get()->pluck('id_tipo');
        $cont=Activo::where('sucursal_id',$request->get('sucursal_origen'))->whereIn('tipo_id',$tipos)->get()->count();
    	$Herramientas->num_equipo='HER-'.($cont+1);
		$Herramientas->update();
    	return Redirect::to('admin/inventario/Herramientas');
    }
    public function show($id)
    {
        $sucursales=Sucursales::where('status',1)->get();
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::get();
        $Empleado=Empleado::get();
    	$Herramientas=Activo::findOrFail($id);
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','5')->get();
        $Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
        $depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
        if(auth()->user()->role_id<3 || auth()->user()->id == $Herramientas->created_user){
    	    return view("inventario.Herramientas.show",["Herramientas"=>$Herramientas,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);
        }else{
            return redirect('/admin/inventario/Herramientas');
        }
    }
    public function edit($id)
    {
        $sucursales=Auth::user()->empleado->sucursales;
        $Herramientas=Activo::findOrFail($id);
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::where('status','=','1')->orderBy('nombre')->get();
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','5')->get();
        $depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
        $Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
        if(auth()->user()->role_id<3 || auth()->user()->id == $Herramientas->created_user){
            return view("inventario.Herramientas.edit",["Herramientas"=>$Herramientas,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);  
        }else{
            return redirect('/admin/inventario/Herramientas');
        }	  
    }
    public function update(HerramientasRequest $request, $id)
    {
    	$Herramientas = Activo::findOrFail($id);
    	$Herramientas->descripcion=$request->get('descripcion');
    	$Herramientas->marca=$request->get('marca');
    	$Herramientas->serie=$request->get('codigo');
    	$Herramientas->modelo=$request->get('modelo');
    	$Herramientas->medida=$request->get('medida');
    	$Herramientas->color=$request->get('color');
    	$Herramientas->tipo_id=$request->get('tipo');
        $Herramientas->costo=$request->get('costo');
    	$Herramientas->nombre_provedor=$request->get('nombre_provedor');
        $Herramientas->id_proveedor=$request->get('id_proveedor');
		$Herramientas->numero_de_pedido=$request->get('numero_de_pedido');
    	$Herramientas->num_factura=$request->get('num_factura');
    	$Herramientas->fecha_compra=$request->get('fecha_compra');
        $Herramientas->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$Herramientas->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
        if($request->get('tasa_depreciacion')!=""){
            $Herramientas->tasa_depreciacion=$request->get('tasa_depreciacion');
        }
        if($request->get('vida_util')!=""){
            $Herramientas->vida_util=$request->get('vida_util');
        }
        if($request->get('fecha_baja')!=""){
            $Herramientas->fecha_baja=$request->get('fecha_baja');
        }
        if($request->get('motivo_baja')!=""){
            $Herramientas->motivo_baja=$request->get('motivo_baja');
        }
    	$Herramientas->departamento_id=$request->get('area_destinada');
    	$Herramientas->puesto_id=$request->get('puesto');
    	$Herramientas->responsable_id=$request->get('nombre_responsable');
    	$Herramientas->observaciones=$request->get('observaciones');
    	$Herramientas->garantia=$request->get('garantia');
        $Herramientas->precio_venta=$request->get('precio_venta');
		$Herramientas->estatus_depreciacion=$request->get('estatus_depreciacion');
        $Herramientas->sucursal_id=$request->get('sucursal_origen');
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/Herramientas/xml/',$Herramientas->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/Herramientas/xml/',$Herramientas->id_equipo_herramienta."-".$file->getClientOriginalName());
    		$Herramientas->xml=$Herramientas->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->storeAs('/archivos/inventario/Herramientas/pdf/',$Herramientas->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/Herramientas/pdf/',$Herramientas->id_equipo_herramienta."-".$file->getClientOriginalName());
    		$Herramientas->pdf=$Herramientas->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('carta_responsiva')){
    		$file=$request->file('carta_responsiva');
    		$file->storeAs('/archivos/inventario/Herramientas/carta_responsiva/',$Herramientas->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/Herramientas/carta_responsiva/',$Herramientas->id_equipo_herramienta."-".$file->getClientOriginalName());
    		$Herramientas->carta_responsiva=$Herramientas->id."-".$file->getClientOriginalName();
    	}
    	$Herramientas->update();
        if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/Herramientas/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/Herramientas/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='5';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
        return Redirect::to('admin/inventario/Herramientas');
    }
    public function destroy(Request $request,$id)
    {
    	$Herramientas = Activo::findOrFail($id);
    	$Herramientas->estado='0';
    	$now = new \DateTime();
		$Herramientas->fecha_baja= $now->format('y-m-d');
		$Herramientas->motivo_baja=$request->input('motivo_baja');
    	$Herramientas->update();
    	return Redirect::to('admin/inventario/Herramientas');
    }
    public function activar(Request $request)
    {
        $id =$request->get('id_act');
        $Herramientas = Activo::findOrFail($id);
        $Herramientas->estado='1';      
        $Herramientas->update();
        return Redirect::to('admin/inventario/Herramientas');
    }
    public function reporte(){
        $sucursales=Auth::user()->empleado->sucursales;
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::get();
        $Empleado=Empleado::where('status',1)->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','5')->whereIn('sucursal_id',Auth::user()->empleado->sucursales->pluck('id_sucursal'))->get();
        return view("inventario.Herramientas.reporte",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado]);
    }
    public function reportes($id){
        return (new HerramientasExport)->forState($id)->download('Herramientas.xlsx');
    }
    public function responsiva($id){
        $Herramientas = Herramientas::findOrFail($id);
        $pdf = PDF::loadView('pdf.Herramientas', compact('Herramientas'));
        return $pdf->stream('carta_responsiva_'.$id.'.pdf');  
    }
    public function responsivas(){
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	return view("inventario.Herramientas.responsivas",["Empleado"=>$Empleado]);
    }
	public function imprimir_responsiva(Request $request){
		$id =$request->get('nombre_responsable');
		$Empleado=Empleado::findOrFail($id);
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','5')->get()->pluck('id_tipo');
        //dd($tipos);
		//$Herramientas = Db::select('select * from herramientas where estado=1 and nombre_responsable='.$id);
        $Herramientas=Activo::where([['estado',1],['responsable_id',$id]])->whereIn('tipo_id',$tipos)->get();
        //dd($Empleado->id_puesto,$Empleado->puesto,$Empleado->puesto->area);
		$pdf = PDF::loadView('pdf.ResponsivaHerramienta', ["Herramientas"=>$Herramientas,"Empleado"=>$Empleado]);
		return $pdf->stream('carta_responsiva_.pdf');
	}
    public function borrar_img(Request $request){
        $var="";
        $result=false;
        foreach($_POST as $nombre_campo => $valor){
            $var.=$valor;
            $Imagen=Imagen::where('id_imagen','=', $valor)->get();
            $data = json_decode($Imagen);
            foreach ($data as $obj) {
                $var.=" - ".$obj->imagen;
                $id=$obj->id_imagen;
                $img = Imagen::find($id);
                $img->delete();
                Storage::disk('public')->delete('/imagenes/inventario/Herramientas/img/'.$obj->imagen);
                //$path=public_path().'/imagenes/inventario/Herramientas/img/'.$obj->imagen;
                //unlink($path);
                $result=true;
            }
       } 
        return "".$result;
    }
}