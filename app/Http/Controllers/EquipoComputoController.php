<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EquipoComputoRequest;
use App\Models\EquipoComputo;
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
use App\Exports\EquipoComputoExport;
use App\Models\Activo;
use App\Models\Puesto;
use App\Models\Sucursales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class EquipoComputoController extends Controller
{
    private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    public function index(Request $request)
    {
    	if($request){
    		$querry=trim($request->get('searchText'));
			$sucursales=$this->sucUsers;
			$tipos=Tipo::where('id_giro',2)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get()->pluck('id_tipo');
    		if(auth()->user()->role_id<3){
				/* $EquipoComputo=EquipoComputo::leftJoin('tipos', 'equipo_computo.tipo', '=', 'tipos.id_tipo')->
				Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_computo','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_computo","num_equipo","equipo_computo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10); */
				$EquipoComputo=Activo::with(['sucursal'=>fn($suc)=>$suc->with('empresa:id_empresa,alias')])->search($querry)->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->whereIn('tipo_id',$tipos)->orderBy('id','ASC')->paginate(10);
			}else{
				/* $EquipoComputo=EquipoComputo::leftJoin('tipos', 'equipo_computo.tipo', '=', 'tipos.id_tipo')->
				where('created_user', '=', auth()->user()->id)
				->Where(function ($query) use($querry){
					$query->where('serie','LIKE','%'.$querry.'%')
					->orWhere('id_equipo_computo','LIKE','%'.$querry.'%')
					->orWhere('descripcion','LIKE','%'.$querry.'%')
					->orWhere('num_equipo','LIKE','%'.$querry.'%')
					->orWhere('tipos.nombre','LIKE','%'.$querry.'%');
				})->select("id_equipo_computo","num_equipo","equipo_computo.estado","descripcion","marca","serie","modelo","tipos.nombre","created_user")
				->paginate(10); */
			}
    		return view('inventario.EquipoComputo.index',["EquipoComputo"=>$EquipoComputo,"searchText"=>$querry]);
    	}
    }
    public function create()
    {
		$sucursales=$this->sucUsers;
        $Departamento=Departamentos::where('status','=','1')->orderBy('nombre')->get();
        //$Puesto=Puestos::where('estado','=','1')->orderBy('nombre')->get();
		$Puesto=Puesto::join('departamento_puesto as dp','puestos.id_puesto','=','dp.id_puesto')->select('puestos.*','dp.id_departamento')->get();
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','2')->whereIn('sucursal_id',$sucursales->pluck('id_sucursal'))->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
    	return view("inventario.EquipoComputo.create",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);
    }
    public function store(EquipoComputoRequest $request)
    {					  
    	$EquipoComputo= new Activo();
    	$EquipoComputo->estado='1';
    	$EquipoComputo->descripcion=$request->get('descripcion');
    	$EquipoComputo->marca=$request->get('marca');
    	$EquipoComputo->serie=$request->get('serie');
    	$EquipoComputo->modelo=$request->get('modelo');
    	$EquipoComputo->color=$request->get('color');
    	$EquipoComputo->tipo_id=$request->get('tipo');
        $EquipoComputo->costo=$request->get('costo');
    	$EquipoComputo->nombre_provedor=$request->get('nombre_provedor');
		$EquipoComputo->id_proveedor=$request->get('id_proveedor');
		$EquipoComputo->numero_de_pedido=$request->get('numero_de_pedido');
    	$EquipoComputo->num_factura=$request->get('num_factura');
    	$EquipoComputo->fecha_compra=$request->get('fecha_compra');
		$EquipoComputo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$EquipoComputo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	$EquipoComputo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	$EquipoComputo->vida_util=$request->get('vida_util');
    	$EquipoComputo->departamento_id=$request->get('area_destinada');
    	$EquipoComputo->puesto_id=$request->get('puesto');
    	$EquipoComputo->responsable_id=$request->get('nombre_responsable');
       	$EquipoComputo->observaciones=$request->get('observaciones');
    	$EquipoComputo->garantia=$request->get('garantia');
        $EquipoComputo->created_user=auth()->user()->id;
		$EquipoComputo->sucursal_id=$request->get('sucursal_origen');
    	$EquipoComputo->save();
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/EquipoComputo/xml/',$EquipoComputo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoComputo/xml/',$EquipoComputo->id_equipo_computo."-".$file->getClientOriginalName());
    		$EquipoComputo->xml=$EquipoComputo->id."-".$file->getClientOriginalName();
    	}  	
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->storeAs('/archivos/inventario/EquipoComputo/pdf/',$EquipoComputo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoComputo/pdf/',$EquipoComputo->id_equipo_computo."-".$file->getClientOriginalName());
    		$EquipoComputo->pdf=$EquipoComputo->id."-".$file->getClientOriginalName();
    	}
		$id=$EquipoComputo->id;
    	if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/EquipoComputo/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/EquipoComputo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='2';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
		$tipos=Tipo::where('id_giro',2)->where('sucursal_id',$request->get('sucursal_origen'))->get()->pluck('id_tipo');
        $cont=Activo::where('sucursal_id',$request->get('sucursal_origen'))->whereIn('tipo_id',$tipos)->get()->count();
    	$EquipoComputo->num_equipo='EQC-'.($cont+1);
		$EquipoComputo->update();
    	return Redirect::to('admin/inventario/EquipoComputo');
    }
    public function show($id)
    {
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::get();
    	$Empleado=Empleado::get();
    	$EquipoComputo=Activo::findOrFail($id);
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','2')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $EquipoComputo->created_user){
			return view("inventario.EquipoComputo.show",["EquipoComputo"=>$EquipoComputo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion]);
		}else{
            return redirect('/admin/inventario/EquipoComputo');
        }
    }
    public function edit($id)
    {
		$sucursales=$this->sucUsers;
		$EquipoComputo=Activo::findOrFail($id);
		$Departamento=Departamentos::where('status','=','1')->get();
		$Puesto=Puesto::where('status','=','1')->orderBy('nombre')->get();
		$Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
		$tipos=Tipo::where('estado','=','1')->where('id_giro','=','2')->where('sucursal_id',$EquipoComputo->sucursal_id)->get();
		$depreciacion=EstadosDepreciacion::where('estado','=','1')->get();
		$Imagen=Imagen::where('estado','=','1')->where('activo_id','=',$id)->get();
		if(auth()->user()->role_id<3 || auth()->user()->id == $EquipoComputo->created_user){
			return view("inventario.EquipoComputo.edit",["EquipoComputo"=>$EquipoComputo,"tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado,"Imagen"=>$Imagen, "Depreciacion"=>$depreciacion,"sucursales"=>$sucursales]);  
		}else{
			return redirect('/admin/inventario/EquipoComputo');
		}
    }
    public function update(EquipoComputoRequest $request, $id)
    {
    	$EquipoComputo = Activo::findOrFail($id);
    	$EquipoComputo->descripcion=$request->get('descripcion');
    	$EquipoComputo->marca=$request->get('marca');
    	$EquipoComputo->serie=$request->get('serie');
    	$EquipoComputo->modelo=$request->get('modelo');
    	$EquipoComputo->color=$request->get('color');
    	$EquipoComputo->tipo_id=$request->get('tipo');
        $EquipoComputo->costo=$request->get('costo');
    	$EquipoComputo->nombre_provedor=$request->get('nombre_provedor');
		$EquipoComputo->id_proveedor=$request->get('id_proveedor');
		$EquipoComputo->numero_de_pedido=$request->get('numero_de_pedido');
    	$EquipoComputo->num_factura=$request->get('num_factura');
    	$EquipoComputo->fecha_compra=$request->get('fecha_compra');
		$EquipoComputo->fecha_vida_util_inicio=$request->get('fecha_vida_util_inicio');
		$EquipoComputo->fecha_depreciacion_inicio=$request->get('fecha_depreciacion_inicio');
    	if($request->get('tasa_depreciacion')!=""){
			$EquipoComputo->tasa_depreciacion=$request->get('tasa_depreciacion');
    	}
    	if($request->get('vida_util')!=""){
			$EquipoComputo->vida_util=$request->get('vida_util');
    	}
    	if($request->get('fecha_baja')!=""){
			$EquipoComputo->fecha_baja=$request->get('fecha_baja');
    	}
    	if($request->get('motivo_baja')!=""){
			$EquipoComputo->motivo_baja=$request->get('motivo_baja');
    	}
    	$EquipoComputo->departamento_id=$request->get('area_destinada');
    	$EquipoComputo->puesto_id=$request->get('puesto');
    	$EquipoComputo->responsable_id=$request->get('nombre_responsable');
    	$EquipoComputo->observaciones=$request->get('observaciones');
    	$EquipoComputo->garantia=$request->get('garantia');
		$EquipoComputo->precio_venta=$request->get('precio_venta');
		$EquipoComputo->estatus_depreciacion=$request->get('estatus_depreciacion');
		$EquipoComputo->sucursal_id=$request->get('sucursal_origen');
    	if($request->hasFile('xml')){
    		$file=$request->file('xml');
    		$file->storeAs('/archivos/inventario/EquipoComputo/xml/',$EquipoComputo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoComputo/xml/',$EquipoComputo->id_equipo_computo."-".$file->getClientOriginalName());
    		$EquipoComputo->xml=$EquipoComputo->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('pdf')){
    		$file=$request->file('pdf');
    		$file->storeAs('/archivos/inventario/EquipoComputo/pdf/',$EquipoComputo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoComputo/pdf/',$EquipoComputo->id_equipo_computo."-".$file->getClientOriginalName());
    		$EquipoComputo->pdf=$EquipoComputo->id."-".$file->getClientOriginalName();
    	}
    	if($request->hasFile('carta_responsiva')){
    		$file=$request->file('carta_responsiva');
    		$file->storeAs('/archivos/inventario/EquipoComputo/carta_responsiva/',$EquipoComputo->id."-".$file->getClientOriginalName(),'public');
    		//$file->move(public_path().'/archivos/inventario/EquipoComputo/carta_responsiva/',$EquipoComputo->id_equipo_computo."-".$file->getClientOriginalName());
    		$EquipoComputo->carta_responsiva=$EquipoComputo->id."-".$file->getClientOriginalName();
    	}
		$EquipoComputo->update();
		if($request->hasFile('foto')) {
            foreach($request->file('foto') as $image) {
                $Imagen= new imagen;
    		    $image->storeAs('/imagenes/inventario/EquipoComputo/img/',$id."_".time()."_".$image->getClientOriginalName(),'public');
                //$image->move(public_path().'/imagenes/inventario/EquipoComputo/img/',$id."_".time()."_".$image->getClientOriginalName());
                $Imagen->activo_id=$id;
                //$Imagen->id_area='2';
                $Imagen->imagen=$id."_".time()."_".$image->getClientOriginalName();
    	        $Imagen->estado='1';
                $image->getClientOriginalName();
                $Imagen->save();
            }
        }
    	return Redirect::to('admin/inventario/EquipoComputo');
    }
    public function destroy(Request $request,$id)
    {
    	$EquipoComputo = Activo::findOrFail($id);
    	$EquipoComputo->estado='0';
    	$now = new \DateTime();
		$EquipoComputo->fecha_baja= $now->format('y-m-d');
		$EquipoComputo->motivo_baja=$request->input('motivo_baja');
    	$EquipoComputo->update();
    	return Redirect::to('admin/inventario/EquipoComputo');
    }
    public function activar(Request $request)
    {
        $id =$request->get('id_act');
        $EquipoComputo = Activo::findOrFail($id);
        $EquipoComputo->estado='1';   
        $EquipoComputo->update();
        return Redirect::to('admin/inventario/EquipoComputo');
    }
    public function reporte(){
        $Departamento=Departamentos::where('status','=','1')->get();
        $Puesto=Puesto::where('status',1)->get();
        $Empleado=Empleado::where('status',1)->get();
    	$tipos=Tipo::where('estado','=','1')->where('id_giro','=','2')->get();
    	return view("inventario.EquipoComputo.reporte",["tipos"=>$tipos,"Departamento"=>$Departamento,"Puesto"=>$Puesto,"Empleado"=>$Empleado]);
    }
    public function reportes($id){
        return (new EquipoComputoExport)->forState($id)->download('EquipoComputo.xlsx');
    }
    public function responsiva($id){
         // responsiva para unico equipo
	    $EquipoComputo = Activo::findOrFail($id);
        $pdf = PDF::loadView('pdf.EquipoComputo', compact('EquipoComputo'));
		return $pdf->stream('carta_responsiva_'.$id.'.pdf');
    }
	public function responsivas(){
           //devuelve vista para elegir a la persona
        $Empleado=Empleado::where('status','=','1')->orderBy('apellido_p')->get();
    	return view("inventario.EquipoComputo.responsivas",["Empleado"=>$Empleado]);
    }
	public function imprimir_responsiva(Request $request){
         //responsiva para todos los equipos de una persona
		 //dd($request);
		$id =$request->get('nombre_responsable');
		$tipos=Tipo::where('id_giro',2)->get()->pluck('id_tipo');
		$Empleado=Empleado::findOrFail($id);
		$EquipoComputo =Activo::where([['estado',1],['responsable_id',$id]])->whereIn('tipo_id',$tipos)->get(); 
		$pdf = PDF::loadView('pdf.Responsiva', ["EquipoComputo"=>$EquipoComputo,"Empleado"=>$Empleado]);
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
				//$path=public_path().'/imagenes/inventario/EquipoComputo/img/'.$obj->imagen;
				//unlink($path);
				$result=true;
			}
		} 
		return "".$result;
	}
}
