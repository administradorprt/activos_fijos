<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EquipoTransporteRequest;
use App\Models\EquipoTransporte;
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
use App\Exports\EquipoTransporteExport;
use App\Models\Activo;
use App\Models\Puesto;
use App\Models\Sucursales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class EquipoTransporteController extends Controller
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
			$tipos=Tipo::where('id_giro',1)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get()->pluck('id_tipo');
			if(auth()->user()->role_id<3){
				/* $equipotransporte=EquipoTransporte::leftJoin('tipos', 'equipo_transporte.tipo', '=', 'tipos.id_tipo')->
				Where(function ($query) use($querry){
					$query->where('vin','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_transporte','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_transporte","num_equipo","equipo_transporte.estado","descripcion","marca","vin","modelo","tipos.nombre","created_user")
				->paginate(10); */
				$equipotransporte=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->search($querry)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->orderBy('id','ASC')->paginate(10);
			}else{
				/* $equipotransporte=EquipoTransporte::leftJoin('tipos', 'equipo_transporte.tipo', '=', 'tipos.id_tipo')->
				where('created_user', '=', auth()->user()->id)
				->Where(function ($query) use($querry){
					$query->where('vin','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_transporte','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_transporte","num_equipo","equipo_transporte.estado","descripcion","marca","vin","modelo","tipos.nombre","created_user")
				->paginate(10); */
			}
    		return view('inventario.EquipoTransporte.index',["EquipoTransporte"=>$equipotransporte,"searchText"=>$querry]);
    	}
    }
    public function create()
    {
		$sucursales=$this->sucUsers;
    	$Departamento=Departamentos::where('status','=','1')->orderBy('nombre')->get();
    	//$Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
		$Puesto=Puesto::join('departamento_puesto as dp','puestos.id_puesto','=','dp.id_puesto')->select('puestos.*','dp.id_departamento')->get();
    	$Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','1')->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
    	return view("inventario.EquipoTransporte.create",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado, "Depreciacion"=>$depreciacion,'sucursales'=>$sucursales]);
    }
    public function store(EquipoTransporteRequest $request)
    {	
    	$EquipoTransporte= new Activo();
    	$EquipoTransporte->estado='1';
    	$EquipoTransporte->descripcion=$request->get('descripcion');
    	$EquipoTransporte->marca=$request->get('marca');
    	$EquipoTransporte->serie=$request->get('vin');
    	$EquipoTransporte->modelo=$request->get('modelo');
    	$EquipoTransporte->num_motor=$request->get('num_motor');
    	$EquipoTransporte->color=$request->get('color');
    	$EquipoTransporte->tipo_id=$request->get('tipo');
        $EquipoTransporte->costo=$request->get('costo');
    	$EquipoTransporte->nombre_provedor=$request->get('nombre_provedor');
		$EquipoTransporte->id_proveedor=$request->get('id_proveedor');
		$EquipoTransporte->numero_de_pedido=$request->get('numero_de_pedido');
    	$EquipoTransporte->num_factura=$request->get('num_factura');
    	$EquipoTransporte->fecha_compra=$request->get('fecha_compra');
		$EquipoTransporte->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$EquipoTransporte->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	$EquipoTransporte->tasa_depreciacion=$request->get('tasa_depreciacion');
    	$EquipoTransporte->vida_util=$request->get('vida_util');
    	$EquipoTransporte->departamento_id=$request->get('area_destinada');
    	$EquipoTransporte->puesto_id=$request->get('puesto');
    	$EquipoTransporte->responsable_id=$request->get('nombre_responsable');
       	$EquipoTransporte->observaciones=$request->get('observaciones');
    	$EquipoTransporte->garantia=$request->get('garantia');
        $EquipoTransporte->created_user=auth()->user()->id;
		$EquipoTransporte->sucursal_id=$request->get('sucursal_origen');
    	$EquipoTransporte->save();
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/EquipoTransporte/xml/',$EquipoTransporte->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoTransporte/xml/',$EquipoTransporte->id_equipo_transporte."-".$file->getClientOriginalName());
    		$EquipoTransporte->xml=$EquipoTransporte->id."-".$file->getClientOriginalName();
    	}  	
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
			$file->storeAs('/archivos/inventario/EquipoTransporte/pdf/',$EquipoTransporte->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoTransporte/pdf/',$EquipoTransporte->id_equipo_transporte."-".$file->getClientOriginalName());
    		$EquipoTransporte->pdf=$EquipoTransporte->id."-".$file->getClientOriginalName();
    	}
    	$id=$EquipoTransporte->id;
    	if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/EquipoTransporte/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/EquipoTransporte/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='1';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
		$tipos=Tipo::where('id_giro',1)->where('sucursal_id',$request->get('sucursal_origen'))->get()->pluck('id_tipo');
        $cont=Activo::where('sucursal_id',$request->get('sucursal_origen'))->whereIn('tipo_id',$tipos)->get()->count();
    	$EquipoTransporte->num_equipo='EQT-'.($cont+1);
		$EquipoTransporte->update();
    	return Redirect::to('admin/inventario/EquipoTransporte');
    }
    public function show($id)
    {
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::get();
        $Empleado=Empleado::get();
    	$EquipoTransporte=Activo::findOrFail($id);
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','1')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $EquipoTransporte->created_user){
			return view("inventario.EquipoTransporte.show",["EquipoTransporte"=>$EquipoTransporte,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion]);
		}else{
			return redirect('admin/inventario/EquipoTransporte');
		}
    }
    public function edit($id)
    {
		$sucursales=$this->sucUsers;
		$EquipoTransporte=Activo::findOrFail($id);
		$Departamento=Departamentos::where('status','=','1')->get();
		$Puesto=Puesto::where('status','=','1')->orderBy('nombre')->get();
		$Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
		$tipos=Tipo::where('estado','=','1')->where('id_giro','=','1')->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $EquipoTransporte->created_user){
			return view("inventario.EquipoTransporte.edit",["EquipoTransporte"=>$EquipoTransporte,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion,'sucursales'=>$sucursales]);  
		}else{
			return redirect('/admin/inventario/EquipoTransporte');
        }
    }
    public function update(EquipoTransporteRequest $request, $id)
    {
    	$EquipoTransporte = Activo::findOrFail($id);
    	$EquipoTransporte->descripcion=$request->get('descripcion');
    	$EquipoTransporte->marca=$request->get('marca');
    	$EquipoTransporte->serie=$request->get('vin');
    	$EquipoTransporte->modelo=$request->get('modelo');
    	$EquipoTransporte->num_motor=$request->get('num_motor');
    	$EquipoTransporte->color=$request->get('color');
    	$EquipoTransporte->tipo_id=$request->get('tipo');
        $EquipoTransporte->costo=$request->get('costo');
    	$EquipoTransporte->nombre_provedor=$request->get('nombre_provedor');
		$EquipoTransporte->id_proveedor=$request->get('id_proveedor');
		$EquipoTransporte->numero_de_pedido=$request->get('numero_de_pedido');
    	$EquipoTransporte->num_factura=$request->get('num_factura');
    	$EquipoTransporte->fecha_compra=$request->get('fecha_compra');
		$EquipoTransporte->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$EquipoTransporte->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	if($request->get('tasa_depreciacion')!=""){
			$EquipoTransporte->tasa_depreciacion=$request->get('tasa_depreciacion');
    	}
    	if($request->get('vida_util')!=""){
			$EquipoTransporte->vida_util=$request->get('vida_util');
    	}
    	if($request->get('fecha_baja')!=""){
			$EquipoTransporte->fecha_baja=$request->get('fecha_baja');
    	}
    	if($request->get('motivo_baja')!=""){
			$EquipoTransporte->motivo_baja=$request->get('motivo_baja');
    	}
    	$EquipoTransporte->departamento_id=$request->get('area_destinada');
    	$EquipoTransporte->puesto_id=$request->get('puesto');
    	$EquipoTransporte->responsable_id=$request->get('nombre_responsable');
    	$EquipoTransporte->observaciones=$request->get('observaciones');
    	$EquipoTransporte->garantia=$request->get('garantia');
		$EquipoTransporte->precio_venta=$request->get('precio_venta');
		$EquipoTransporte->estatus_depreciacion=$request->get('estatus_depreciacion');
		$EquipoTransporte->sucursal_id=$request->get('sucursal_origen');
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		//$file->move(public_path().'/archivos/inventario/EquipoTransporte/xml/',$EquipoTransporte->id_equipo_transporte."-".$file->getClientOriginalName());
    		$file->storeAs('/archivos/inventario/EquipoTransporte/xml/',$EquipoTransporte->id."-".$file->getClientOriginalName(),'public');
    		$EquipoTransporte->xml=$EquipoTransporte->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		//$file->move(public_path().'/archivos/inventario/EquipoTransporte/pdf/',$EquipoTransporte->id_equipo_transporte."-".$file->getClientOriginalName());
			$file->storeAs('/archivos/inventario/EquipoTransporte/pdf/',$EquipoTransporte->id."-".$file->getClientOriginalName(),'public');
    		$EquipoTransporte->pdf=$EquipoTransporte->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('carta_responsiva')){
    		$file=$request->file('carta_responsiva');
    		//$file->move(public_path().'/archivos/inventario/EquipoTransporte/carta_responsiva/',$EquipoTransporte->id_equipo_transporte."-".$file->getClientOriginalName());
			$file->storeAs('/archivos/inventario/EquipoTransporte/carta_responsiva/',$EquipoTransporte->id."-".$file->getClientOriginalName(),'public');
    		$EquipoTransporte->carta_responsiva=$EquipoTransporte->id."-".$file->getClientOriginalName();
    	}
    	$EquipoTransporte->update();
		if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/EquipoTransporte/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/EquipoTransporte/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='1';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
    	return Redirect::to('admin/inventario/EquipoTransporte');
    }
    public function destroy(Request $request, $id)
    {
    	$EquipoTransporte = Activo::findOrFail($id);
    	$EquipoTransporte->estado='0';
    	$now = new \DateTime();
		$EquipoTransporte->fecha_baja= $now->format('y-m-d');
		$EquipoTransporte->motivo_baja= $request->input('motivo_baja');
    	$EquipoTransporte->update();
    	return Redirect::to('admin/inventario/EquipoTransporte');
    }
    public function activar(Request $request)
    {
        $id =$request->get('id_act');
        $EquipoTransporte = Activo::findOrFail($id);
        $EquipoTransporte->estado='1';   
        $EquipoTransporte->update();
        return Redirect::to('admin/inventario/EquipoTransporte');
    }
    public function reporte(){
    	$Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::get();
        $Empleado=Empleado::get();
        $tipos=Tipo::where('estado','=','1')->where('id_giro','=','1')->get();
    	return view("inventario.EquipoTransporte.reporte",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado]);
    }
    public function reportes($id){
       return (new EquipoTransporteExport)->forState($id)->download('EquipoTransporte.xlsx');
    }
    public function responsiva($id){
        // responsiva para unico equipo
	    $EquipoTransporte = Activo::findOrFail($id);
        $pdf = PDF::loadView('pdf.EquipoTransporte', compact('EquipoTransporte'));
        return $pdf->stream('carta_responsiva_'.$id.'.pdf');
    }
	public function responsivas(){
        //devuelve vista para elegir a la persona
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	return view("inventario.EquipoTransporte.responsivas",["Empleado"=>$Empleado]);
    }
	public function imprimir_responsiva(Request $request){
        //responsiva para todos los equipos de una persona
		$id =$request->get('nombre_responsable');
		$tipos=Tipo::where('id_giro',1)->get()->pluck('id_tipo');
		$Empleado=Empleado::findOrFail($id);
		$EquipoTransporte = Activo::where([['estado',1],['responsable_id',$id]])->whereIn('tipo_id',$tipos)->get();
		$pdf = PDF::loadView('pdf.ResponsivaEquipoTransporte', ["EquipoTransporte"=>$EquipoTransporte,"Empleado"=>$Empleado]);
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
                //$path=public_path().'/imagenes/inventario/EquipoTransporte/img/'.$obj->imagen;
                //unlink($path);
                $result=true;
            }
       } 
        return "".$result;
    }
}
