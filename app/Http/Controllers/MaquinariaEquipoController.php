<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MaquinariaEquipoRequest;
use App\Models\MaquinariaEquipo;
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
use App\Exports\MaquinariaEquipoExport;
use App\Models\Activo;
use App\Models\Puesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MaquinariaEquipoController extends Controller
{
	private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    public function index(Request $request)
    {
    	if($request)
    	{
    		$querry=trim($request->get('searchText'));
			$sucursales=$this->sucUsers;
			$tipos=Tipo::where('id_giro',4)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get()->pluck('id_tipo');
			if(auth()->user()->role_id<3){
				 /* $MaquinariaEquipo=MaquinariaEquipo::leftJoin('tipos', 'maquinaria_y_equipo.tipo', '=', 'tipos.id_tipo')->
				Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_maquinaria','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_maquinaria","num_equipo","maquinaria_y_equipo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10); */
				$MaquinariaEquipo=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->search($querry)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->orderBy('id','ASC')->paginate(10);
			}else{
                 /* $MaquinariaEquipo=MaquinariaEquipo::leftJoin('tipos', 'maquinaria_y_equipo.tipo', '=', 'tipos.id_tipo')->
                where('created_user', '=', auth()->user()->id)
				->Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_maquinaria','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_maquinaria","num_equipo","maquinaria_y_equipo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10); */
            }
    		return view('inventario.MaquinariaEquipo.index',["MaquinariaEquipo"=>$MaquinariaEquipo,"searchText"=>$querry]);
    	}
    }
    public function create()
    {
		$sucursales=$this->sucUsers;
    	$Departamento=Departamentos::where('status','=','1')->orderBy('nombre')->get();
    	//$Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
		$Puesto=Puesto::join('departamento_puesto as dp','puestos.id_puesto','=','dp.id_puesto')->select('puestos.*','dp.id_departamento')->get();
    	$Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','4')->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
    	return view("inventario.MaquinariaEquipo.create",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);
    }
    public function store(MaquinariaEquipoRequest $request)
    {					  
    	$MaquinariaEquipo= new Activo();
    	$MaquinariaEquipo->estado='1';
    	$MaquinariaEquipo->descripcion=$request->get('descripcion');
    	$MaquinariaEquipo->marca=$request->get('marca');
    	$MaquinariaEquipo->serie=$request->get('serie');
    	$MaquinariaEquipo->modelo=$request->get('modelo');
    	$MaquinariaEquipo->color=$request->get('color');
    	$MaquinariaEquipo->tipo_id=$request->get('tipo');
      	$MaquinariaEquipo->costo=$request->get('costo');
    	$MaquinariaEquipo->nombre_provedor=$request->get('nombre_provedor');
		$MaquinariaEquipo->id_proveedor=$request->get('id_proveedor');
		$MaquinariaEquipo->numero_de_pedido=$request->get('numero_de_pedido');
    	$MaquinariaEquipo->num_factura=$request->get('num_factura');
    	$MaquinariaEquipo->fecha_compra=$request->get('fecha_compra');
		$MaquinariaEquipo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$MaquinariaEquipo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	$MaquinariaEquipo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	$MaquinariaEquipo->vida_util=$request->get('vida_util');
    	$MaquinariaEquipo->departamento_id=$request->get('area_destinada');
    	$MaquinariaEquipo->puesto_id=$request->get('puesto');
    	$MaquinariaEquipo->responsable_id=$request->get('nombre_responsable');
       	$MaquinariaEquipo->observaciones=$request->get('observaciones');
    	$MaquinariaEquipo->garantia=$request->get('garantia');
        $MaquinariaEquipo->created_user=auth()->user()->id;
		$MaquinariaEquipo->sucursal_id=$request->get('sucursal_origen');
    	$MaquinariaEquipo->save();
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/MaquinariaEquipo/xml/',$MaquinariaEquipo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/MaquinariaEquipo/xml/',$MaquinariaEquipo->id_equipo_maquinaria."-".$file->getClientOriginalName());
    		$MaquinariaEquipo->xml=$MaquinariaEquipo->id."-".$file->getClientOriginalName();
    	}  	
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->storeAs('/archivos/inventario/MaquinariaEquipo/pdf/',$MaquinariaEquipo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/MaquinariaEquipo/pdf/',$MaquinariaEquipo->id_equipo_maquinaria."-".$file->getClientOriginalName());
    		$MaquinariaEquipo->pdf=$MaquinariaEquipo->id."-".$file->getClientOriginalName();
    	}
    	$id=$MaquinariaEquipo->id;
    	if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/MaquinariaEquipo/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/MaquinariaEquipo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='4';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
		$tipos=Tipo::where('id_giro',4)->where('sucursal_id',$request->get('sucursal_origen'))->get()->pluck('id_tipo');
        $cont=Activo::where('sucursal_id',$request->get('sucursal_origen'))->whereIn('tipo_id',$tipos)->get()->count();
    	$MaquinariaEquipo->num_equipo='MAE-'.($cont+1);
		$MaquinariaEquipo->update();
    	return Redirect::to('admin/inventario/MaquinariaEquipo');
    }
    public function show($id)
    {
    	$Departamento=Departamentos::where('status','=','1')->get();
    	$Puesto=Puesto::get();
      	$Empleado=Empleado::get();
    	$MaquinariaEquipo=Activo::findOrFail($id);
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','4')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $MaquinariaEquipo->created_user){
			return view("inventario.MaquinariaEquipo.show",["MaquinariaEquipo"=>$MaquinariaEquipo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion]);
		}else{
			return redirect('/admin/inventario/MaquinariaEquipo');
        }
    }
    public function edit($id)
    {
		$sucursales=$this->sucUsers;
    	$MaquinariaEquipo=Activo::findOrFail($id);
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::where('status','=','1')->orderBy('nombre')->get();
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','4')->where('sucursal_id',$MaquinariaEquipo->sucursal_id)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $MaquinariaEquipo->created_user){
			return view("inventario.MaquinariaEquipo.edit",["MaquinariaEquipo"=>$MaquinariaEquipo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);  
		}else{
			return redirect('/admin/inventario/MaquinariaEquipo');
		}
    }
    public function update(MaquinariaEquipoRequest $request, $id)
    {
    	$MaquinariaEquipo = Activo::findOrFail($id);
    	$MaquinariaEquipo->descripcion=$request->get('descripcion');
    	$MaquinariaEquipo->marca=$request->get('marca');
    	$MaquinariaEquipo->serie=$request->get('serie');
    	$MaquinariaEquipo->modelo=$request->get('modelo');
    	$MaquinariaEquipo->color=$request->get('color');
    	$MaquinariaEquipo->tipo_id=$request->get('tipo');
        $MaquinariaEquipo->costo=$request->get('costo');
    	$MaquinariaEquipo->nombre_provedor=$request->get('nombre_provedor');
		$MaquinariaEquipo->id_proveedor=$request->get('id_proveedor');
		$MaquinariaEquipo->numero_de_pedido=$request->get('numero_de_pedido');
    	$MaquinariaEquipo->num_factura=$request->get('num_factura');
    	$MaquinariaEquipo->fecha_compra=$request->get('fecha_compra');
		$MaquinariaEquipo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$MaquinariaEquipo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	if($request->get('tasa_depreciacion')!=""){
			$MaquinariaEquipo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	}
    	if($request->get('vida_util')!=""){
			$MaquinariaEquipo->vida_util=$request->get('vida_util');
    	}
    	if($request->get('fecha_baja')!=""){
			$MaquinariaEquipo->fecha_baja=$request->get('fecha_baja');
    	}
    	if($request->get('motivo_baja')!=""){
			$MaquinariaEquipo->motivo_baja=$request->get('motivo_baja');
    	}
    	$MaquinariaEquipo->departamento_id=$request->get('area_destinada');
    	$MaquinariaEquipo->puesto_id=$request->get('puesto');
    	$MaquinariaEquipo->responsable_id=$request->get('nombre_responsable');
    	$MaquinariaEquipo->observaciones=$request->get('observaciones');
    	$MaquinariaEquipo->garantia=$request->get('garantia');
		$MaquinariaEquipo->precio_venta=$request->get('precio_venta');
		$MaquinariaEquipo->estatus_depreciacion=$request->get('estatus_depreciacion');
		$MaquinariaEquipo->sucursal_id=$request->get('sucursal_origen');
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/MaquinariaEquipo/xml/',$MaquinariaEquipo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/MaquinariaEquipo/xml/',$MaquinariaEquipo->id_equipo_maquinaria."-".$file->getClientOriginalName());
    		$MaquinariaEquipo->xml=$MaquinariaEquipo->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->storeAs('/archivos/inventario/MaquinariaEquipo/pdf/',$MaquinariaEquipo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/MaquinariaEquipo/pdf/',$MaquinariaEquipo->id_equipo_maquinaria."-".$file->getClientOriginalName());
    		$MaquinariaEquipo->pdf=$MaquinariaEquipo->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('carta_responsiva')){
    		$file=$request->file('carta_responsiva');
			$file->storeAs('/archivos/inventario/MaquinariaEquipo/carta_responsiva/',$MaquinariaEquipo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/MaquinariaEquipo/carta_responsiva/',$MaquinariaEquipo->id_equipo_maquinaria."-".$file->getClientOriginalName());
    		$MaquinariaEquipo->carta_responsiva=$MaquinariaEquipo->id."-".$file->getClientOriginalName();
    	}
    	$MaquinariaEquipo->update();
		if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/MaquinariaEquipo/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/MaquinariaEquipo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='4';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
    	return Redirect::to('admin/inventario/MaquinariaEquipo');
    }
    public function destroy(Request $request,$id)
    {
    	$MaquinariaEquipo = Activo::findOrFail($id);
    	$MaquinariaEquipo->estado='0';
    	$now = new \DateTime();
		$MaquinariaEquipo->fecha_baja= $now->format('y-m-d');
		$MaquinariaEquipo->motivo_baja=$request->input('motivo_baja');
    	$MaquinariaEquipo->update();
    	return Redirect::to('admin/inventario/MaquinariaEquipo');
    }
    public function activar(Request $request)
    {
        $id =$request->get('id_act');
        $MaquinariaEquipo = Activo::findOrFail($id);
        $MaquinariaEquipo->estado='1';   
        $MaquinariaEquipo->update();
        return Redirect::to('admin/inventario/MaquinariaEquipo');
    }
    public function reporte(){
    	$Departamento=Departamentos::where('status','=','1')->get();
    	$Puesto=Puesto::get();
    	$Empleado=Empleado::where('status',1)->get();
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','4')->get();
    	return view("inventario.MaquinariaEquipo.reporte",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado]);
    }
    public function reportes($id){
        return (new MaquinariaEquipoExport)->forState($id)->download('MaquinariaEquipo.xlsx');
    }
    public function responsiva($id){
	    $MaquinariaEquipo = Activo::findOrFail($id);
        $pdf = PDF::loadView('pdf.MaquinariaEquipo', compact('MaquinariaEquipo'));
		return $pdf->stream('carta_responsiva_'.$id.'.pdf');	
    }
	public function responsivas(){
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	return view("inventario.MaquinariaEquipo.responsivas",["Empleado"=>$Empleado]);
    }
	public function imprimir_responsiva(Request $request){
		$id =$request->get('nombre_responsable');
		$tipos=Tipo::where('id_giro',4)->get()->pluck('id_tipo');
		$Empleado=Empleado::findOrFail($id);
		$MaquinariaEquipo = Activo::where([['estado',1],['responsable_id',$id]])->whereIn('tipo_id',$tipos)->get();
		$pdf = PDF::loadView('pdf.ResponsivaMaquinariaEquipo', ["MaquinariaEquipo"=>$MaquinariaEquipo,"Empleado"=>$Empleado]);
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
				Storage::disk('public')->delete('/imagenes/inventario/EquipoTransporte/img/'.$obj->imagen);
				//$path=public_path().'/imagenes/inventario/MaquinariaEquipo/img/'.$obj->imagen;
				//unlink($path);
				$result=true;
			}
		} 
		return "".$result;
	}
}