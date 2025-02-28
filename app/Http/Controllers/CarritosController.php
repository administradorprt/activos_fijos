<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\ActivosCarrito;
use App\Models\Cajon;
use App\Models\Carrito;
use App\Models\Empleado;
use App\Models\Giro;
use App\Models\Tipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CarritosController extends Controller
{
    private $sucUsers;
    public function __construct()
    {
		$this->sucUsers=Auth::user()->empleado->sucursales;
    }
    public function index($tipo){
        $carritos=Carrito::where('giro_id',$tipo)->get();
        return view('inventario.Carritos.index',compact('tipo','carritos'));
    }
    public function create($tipo){
        $sucursales=$this->sucUsers;
        return view('inventario.Carritos.create',compact('sucursales','tipo'));
    }
    public function store(Request $request,$tipo){
        //return response()->json($request->carrito);
        $validator = Validator::make($request->all(), [
            'titulo'=>'required|min:3',
            'sucursal'=>'required',
            'user'=>'required',
            'carrito' => 'required|array|min:1',
            'carrito.*.name' => 'required|string|min:3',
            'carrito.*.acts' => 'required|array|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $carrito=new Carrito();
            $carrito->nombre=$request->titulo;
            $carrito->giro_id=$tipo;
            $carrito->sucursal_id=$request->sucursal;
            $carrito->user_asignado=$request->user;
            $carrito->save();
            //guardamos los activos de cada cajon 
            foreach($request->carrito as $cajon){
                $newCajon=new Cajon();
                $newCajon->carrito_id=$carrito->id;
                $newCajon->name=$cajon['name'];
                $newCajon->status=1;
                $newCajon->save();
                foreach($cajon['acts'] as $activo){
                    $activoCarrito=new ActivosCarrito();
                    $activoCarrito->activo_id=$activo;
                    $activoCarrito->cajon_id=$newCajon->id;
                    $activoCarrito->status=1;
                    $activoCarrito->save();
                }
            }
            return response()->json(['message' => 'Datos guardados correctamente']);
        } catch (Exception $e) {
            Log::error('error',['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(Carrito $carrito) {
        return view('inventario.Carritos.show',compact('carrito'));
    }
    public function edit(Carrito $carrito) {
        $sucursales=$this->sucUsers;
        $empleados=Empleado::where([['status',1],['id_sucursal_principal',$carrito->sucursal_id]])->orderBy('nombres','ASC')->select('id_empleado','n_empleado','nombres','apellido_m','apellido_p','id_puesto','id_sucursal_principal',)->get();
        return view('inventario.Carritos.edit',compact('carrito','sucursales','empleados'));
    }
    public function update(Request $request, Carrito $carrito) {
        $request->validate([
            'titulo'=>'required|min:3',
            'sucursal'=>'required',
            'user'=>'required',
        ]);
        try {
            $carrito->nombre=$request->titulo;
            $carrito->sucursal_id=$request->sucursal;
            $carrito->user_asignado=$request->user;
            $carrito->update();

            if($request->cajones){
                foreach ($request->cajones as $key => $cajon) {
                    foreach ($cajon as $activo){
                        ActivosCarrito::firstWhere([['activo_id',$activo],['cajon_id',$key]])->delete();
                    }
                }
            }
            return to_route('carritos.edit',$carrito->id);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error'=> 'Error al guardar el registro: '.$e->getMessage()]);
        }

    }
    /**
     * Función para eliminar un cajon junto con sus activos asignados
     */
    public function cajonDelete(Cajon $cajon){
        try {
            $carrito=$cajon->carrito_id;
            ActivosCarrito::where('cajon_id',$cajon->id)->delete();
            $cajon->delete();
            return to_route('carritos.edit',$carrito);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error'=> 'Error al guardar el registro: '.$e->getMessage()]);
        }
    }
    public function cajonAddActivos(Cajon $cajon){
        $cajones=Cajon::where('carrito_id',$cajon->carrito_id)->get()->pluck('id');
        $tipos=Tipo::where('id_giro',$cajon->carrito->giro_id)->get()->pluck('id_tipo');
        $actsCajones=ActivosCarrito::all()->pluck('activo_id');
        $activos=Activo::whereIn('tipo_id',$tipos)->whereNotIn('id',$actsCajones)->get();
        return view('inventario.Carritos.addActivoCarrito', compact('activos', 'cajon'));
    }
    public function cajonActivosStore(Request $request,Cajon $cajon){
        $request->validate(['activo'=>'required|array|min:1']);
        foreach($request->activo as $activo) {
            $actCajon=new ActivosCarrito();
            $actCajon->activo_id=$activo;
            $actCajon->cajon_id=$cajon->id;
            $actCajon->status=1;
            $actCajon->save();
        }
        return to_route('carritos.edit',$cajon->carrito_id);
    }
}
