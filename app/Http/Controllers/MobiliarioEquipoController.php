<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MobiliarioEquipoRequest;
use App\Models\MobiliarioEquipo;
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
use App\Exports\MobiliarioEquipoExport;
use Maatwebsite\Excel\Facades\Excel;

class MobiliarioEquipoController extends Controller
{
    public function index(Request $request)
    {
    	if($request)
    	{
    		$querry=trim($request->get('searchText'));
			if(auth()->user()->role_id<3){
				$MobiliarioEquipo=MobiliarioEquipo::leftJoin('tipos', 'mobiliario_y_equipo.tipo', '=', 'tipos.id_tipo')->
				Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_mobiliario','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_mobiliario","num_equipo","mobiliario_y_equipo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10);
			}else{
				$MobiliarioEquipo=MobiliarioEquipo::leftJoin('tipos', 'mobiliario_y_equipo.tipo', '=', 'tipos.id_tipo')->
				where('created_user', '=', auth()->user()->id)
				->Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_mobiliario','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_mobiliario","num_equipo","mobiliario_y_equipo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10);
			}
			return view('inventario.MobiliarioEquipo.index',["MobiliarioEquipo"=>$MobiliarioEquipo,"searchText"=>$querry]);
    	}
    }
    public function create()
    {
        $Departamento=Departamentos::where('estado','=','1')->orderBy('nombre')->get();
        $Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
        $Empleado=Empleado::where('estado','=','1')->orderBy('apellido_paterno')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','3')->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
    	return view("inventario.MobiliarioEquipo.create",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado, "Depreciacion"=>$depreciacion]);
    }
    public function store(MobiliarioEquipoRequest $request)
    {					  
    	$MobiliarioEquipo= new MobiliarioEquipo;
    	$MobiliarioEquipo->estado='1';
    	$MobiliarioEquipo->descripcion=$request->get('descripcion');
    	$MobiliarioEquipo->marca=$request->get('marca');
    	$MobiliarioEquipo->serie=$request->get('serie');
    	$MobiliarioEquipo->modelo=$request->get('modelo');
    	$MobiliarioEquipo->medida=$request->get('medida');
    	$MobiliarioEquipo->color=$request->get('color');
    	$MobiliarioEquipo->tipo=$request->get('tipo');
        $MobiliarioEquipo->costo=$request->get('costo');
    	$MobiliarioEquipo->nombre_provedor=$request->get('nombre_provedor');
		$MobiliarioEquipo->id_proveedor=$request->get('id_proveedor');
		$MobiliarioEquipo->numero_de_pedido=$request->get('numero_de_pedido');
    	$MobiliarioEquipo->num_factura=$request->get('num_factura');
    	$MobiliarioEquipo->fecha_compra=$request->get('fecha_compra');
		$MobiliarioEquipo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$MobiliarioEquipo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	$MobiliarioEquipo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	$MobiliarioEquipo->vida_util=$request->get('vida_util');
    	$MobiliarioEquipo->area_destinada=$request->get('area_destinada');
    	$MobiliarioEquipo->puesto=$request->get('puesto');
    	$MobiliarioEquipo->nombre_responsable=$request->get('nombre_responsable');
       	$MobiliarioEquipo->observaciones=$request->get('observaciones');
    	$MobiliarioEquipo->garantia=$request->get('garantia');
        $MobiliarioEquipo->created_user=auth()->user()->id;
    	$MobiliarioEquipo->save();
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->move(public_path().'/archivos/inventario/MobiliarioEquipo/xml/',$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName());
    		$MobiliarioEquipo->xml=$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName();
    	}  	
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->move(public_path().'/archivos/inventario/MobiliarioEquipo/pdf/',$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName());
    		$MobiliarioEquipo->pdf=$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName();
    	}
		$id=$MobiliarioEquipo->id_equipo_mobiliario;
    	if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
                $image->move(public_path().'/imagenes/inventario/MobiliarioEquipo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->id_equipo=$id;
                $Imagen->id_area='3';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
		$MobiliarioEquipo->num_equipo='MOE-'.$MobiliarioEquipo->id_equipo_mobiliario;
		$MobiliarioEquipo->update();
    	return Redirect::to('admin/inventario/MobiliarioEquipo');
    }
    public function show($id)
    {
        $Departamento=Departamentos::where('estado','=','1')->get();
        $Puesto=Puestos::get();
        $Empleado=Empleado::get();
    	$MobiliarioEquipo=MobiliarioEquipo::findOrFail($id);
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','3')->get();
		$Imagen=Imagen::where('estado','=','1')->where('id_equipo','=',$id)->where('id_area', '=', 3)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $MobiliarioEquipo->created_user){
			return view("inventario.MobiliarioEquipo.show",["MobiliarioEquipo"=>$MobiliarioEquipo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion]);
		}else{
			return redirect('/admin/inventario/MobiliarioEquipo');
		}
    }
    public function edit($id)
    {
    	$MobiliarioEquipo=MobiliarioEquipo::findOrFail($id);
        $Departamento=Departamentos::where('estado','=','1')->get();
        $Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
        $Empleado=Empleado::where('estado','=','1')->orderBy('apellido_paterno')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','3')->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		$Imagen=Imagen::where('estado','=','1')->where('id_equipo','=',$id)->where('id_area', '=', 3)->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $MobiliarioEquipo->created_user){
			return view("inventario.MobiliarioEquipo.edit",["MobiliarioEquipo"=>$MobiliarioEquipo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion]);  
		}else{
			return redirect('/admin/inventario/MobiliarioEquipo');
		}
    }
    public function update(MobiliarioEquipoRequest $request, $id)
    {
    	$MobiliarioEquipo = MobiliarioEquipo::findOrFail($id);
    	$MobiliarioEquipo->descripcion=$request->get('descripcion');
    	$MobiliarioEquipo->marca=$request->get('marca');
    	$MobiliarioEquipo->serie=$request->get('serie');
    	$MobiliarioEquipo->modelo=$request->get('modelo');
    	$MobiliarioEquipo->medida=$request->get('medida');
    	$MobiliarioEquipo->color=$request->get('color');
    	$MobiliarioEquipo->tipo=$request->get('tipo');
        $MobiliarioEquipo->costo=$request->get('costo');
    	$MobiliarioEquipo->nombre_provedor=$request->get('nombre_provedor');
		$MobiliarioEquipo->id_proveedor=$request->get('id_proveedor');
		$MobiliarioEquipo->numero_de_pedido=$request->get('numero_de_pedido');
    	$MobiliarioEquipo->num_factura=$request->get('num_factura');
    	$MobiliarioEquipo->fecha_compra=$request->get('fecha_compra');
		$MobiliarioEquipo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$MobiliarioEquipo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	if($request->get('tasa_depreciacion')!=""){
			$MobiliarioEquipo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	}
    	if($request->get('vida_util')!=""){
			$MobiliarioEquipo->vida_util=$request->get('vida_util');
    	}
    	if($request->get('fecha_baja')!=""){
			$MobiliarioEquipo->fecha_baja=$request->get('fecha_baja');
    	}
    	if($request->get('motivo_baja')!=""){
			$MobiliarioEquipo->motivo_baja=$request->get('motivo_baja');
    	}
    	$MobiliarioEquipo->area_destinada=$request->get('area_destinada');
    	$MobiliarioEquipo->puesto=$request->get('puesto');
    	$MobiliarioEquipo->nombre_responsable=$request->get('nombre_responsable');
    	$MobiliarioEquipo->observaciones=$request->get('observaciones');
    	$MobiliarioEquipo->garantia=$request->get('garantia');
		$MobiliarioEquipo->precio_venta=$request->get('precio_venta');
		$MobiliarioEquipo->estatus_depreciacion=$request->get('estatus_depreciacion');
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->move(public_path().'/archivos/inventario/MobiliarioEquipo/xml/',$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName());
    		$MobiliarioEquipo->xml=$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->move(public_path().'/archivos/inventario/MobiliarioEquipo/pdf/',$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName());
    		$MobiliarioEquipo->pdf=$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('carta_responsiva')){
    		$file=$request->file('carta_responsiva');
    		$file->move(public_path().'/archivos/inventario/MobiliarioEquipo/carta_responsiva/',$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName());
    		$MobiliarioEquipo->carta_responsiva=$MobiliarioEquipo->id_equipo_mobiliario."-".$file->getClientOriginalName();
    	}
    	$MobiliarioEquipo->update();
		if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
                $image->move(public_path().'/imagenes/inventario/MobiliarioEquipo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->id_equipo=$id;
                $Imagen->id_area='3';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
    	return Redirect::to('admin/inventario/MobiliarioEquipo');
    }
    public function destroy(Request $request,$id)
    {
    	$MobiliarioEquipo = MobiliarioEquipo::findOrFail($id);
    	$MobiliarioEquipo->estado='0';
    	$now = new \DateTime();
		$MobiliarioEquipo->fecha_baja= $now->format('y-m-d');
		$MobiliarioEquipo->motivo_baja=$request->input('motivo_baja');
    	$MobiliarioEquipo->update();
    	return Redirect::to('admin/inventario/MobiliarioEquipo');
    }
    public function activar(Request $request)
    {
        $id =$request->get('id_act');
        $MobiliarioEquipo = MobiliarioEquipo::findOrFail($id);
        $MobiliarioEquipo->estado='1';   
        $MobiliarioEquipo->update();
        return Redirect::to('admin/inventario/MobiliarioEquipo');
    }
    public function reporte(){
    	$Departamento=Departamentos::where('estado','=','1')->get();
        $Puesto=Puestos::get();
    	$Empleado=Empleado::get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','3')->get();
    	return view("inventario.MobiliarioEquipo.reporte",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado]);
    }
    public function reportes($id){
        return (new MobiliarioEquipoExport)->forState($id)->download('MobiliarioEquipo.xlsx');
    }
    public function responsiva($id){
	    $MobiliarioEquipo = MobiliarioEquipo::findOrFail($id);
        $pdf = PDF::loadView('pdf.MobiliarioEquipo', compact('MobiliarioEquipo'));
		return $pdf->stream('carta_responsiva_'.$id.'.pdf');	
    }
	public function responsivas(){
        $Empleado=Empleado::where('estado','=','1')->orderBy('apellido_paterno')->get();
    	return view("inventario.MobiliarioEquipo.responsivas",["Empleado"=>$Empleado]);
    }
	public function imprimir_responsiva(Request $request){
		$id =$request->get('nombre_responsable');
		$Empleado=Empleado::findOrFail($id);
		$MobiliarioEquipo = DB::select('select * from mobiliario_y_equipo where estado=1 and nombre_responsable='.$id);
		$pdf = PDF::loadView('pdf.ResponsivaMobiliarioEquipo', ["MobiliarioEquipo"=>$MobiliarioEquipo,"Empleado"=>$Empleado]);
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
                $path=public_path().'/imagenes/inventario/MobiliarioEquipo/img/'.$obj->imagen;
                unlink($path);
                $result=true;
            }
       } 
    	return "".$result;
    }
}
