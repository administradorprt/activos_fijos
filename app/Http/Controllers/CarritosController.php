<?php

namespace App\Http\Controllers;

use App\Models\ActivosCarrito;
use App\Models\Cajon;
use App\Models\Carrito;
use App\Models\Giro;
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
        return view('inventario.Carritos.index',compact('tipo'));
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
            $carrito->qr='23';
            $carrito->save();
            //guardamos los activos de cada cajon 
            foreach($request->carrito as $cajon){
                $newCajon=new Cajon();
                $newCajon->carrito_id=1;
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
}
