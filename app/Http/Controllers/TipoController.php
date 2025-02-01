<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests\TipoRequest;
use DB;
use App\Models\Tipo;
use App\Models\Empleado;
class TipoController extends Controller
{
    //
    public function index(Request $request)
    {
        //DB::enableQueryLog();
    	if($request){
    		$querry=trim($request->get('searchText'));
    		$Tipo=Tipo::join('giros', 'giros.id_giro', '=', 'tipos.id_giro')
    		->where('tipos.nombre','LIKE','%'.$querry.'%')
    		->where('tipos.estado','=','1')
    		->orWhere('giros.nombre','LIKE','%'.$querry.'%')
    		->where('tipos.estado','=','1')
            ->orWhere('tipos.id_tipo','LIKE','%'.$querry.'%')
            ->where('tipos.estado','=','1')
    		->select('tipos.*', 'giros.nombre as giro_nombre')
    		->orderBy('tipos.id_tipo')
    		->paginate(10);
    		return view('inventario.Tipo.index',["Tipo"=>$Tipo,"searchText"=>$querry]);
    	}
       // dd(DB::getQueryLog());
    }
    public function create()
    {
        $Giro=DB::table('giros')->get();
        return view("inventario.Tipo.create",["Giro"=>$Giro]);
    }
    public function store(TipoRequest $request)
    {
    	$Tipo= new Tipo;
    	$Tipo->nombre=$request->get('nombre');
    	$Tipo->id_giro=$request->get('giro');
    	$Tipo->estado='1';
    	$Tipo->save();
    	return Redirect::to('admin/inventario/Tipo');
    }
    public function show($id)
    {
    	return view("inventario.Tipo.show",["Tipo"=>Tipo::findOrFail($id)]);
    }
    public function edit($id)
    {
        $Tipo=Tipo::findOrFail($id);
        $Giro=DB::table('giros')->get();
        return view("inventario.Tipo.edit",["Tipo"=>$Tipo,"Giro"=>$Giro]);	
    }
    public function update(TipoRequest $request, $id)
    {
    	$Tipo = Tipo::findOrFail($id);
    	$Tipo->id_giro=$request->get('giro');
    	$Tipo->nombre=$request->get('nombre');
    	$Tipo->update();
    	return Redirect::to('admin/inventario/Tipo');
    }
    public function destroy($id)
    {
    	$Tipo = Tipo::findOrFail($id);
    	$Tipo->estado='0';
    	$Tipo->update();
    	return Redirect::to('admin/inventario/Tipo');
    }
}
