<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\EquipoTransporte;
use App\Models\EquipoComputo;
use App\Models\MobiliarioEquipo;
use App\Models\Herramientas;
use App\Models\MaquinariaEquipo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportEquipo;
use App\Models\Activo;
use App\Models\Tipo;
use Illuminate\Support\Facades\Auth;

class CargaController extends Controller
{
    public $sucursales;
    public function __construct() {
        $this->sucursales=Auth::user()->empleado->sucursales;
    }
    public function index()
    {
    	return view('inventario.Carga.index');
    }
    public function index_eqt()
    {
        $respuesta='';
    	return view('inventario.Carga.index_eqt', ["Respuesta"=>$respuesta,"sucursales"=>$this->sucursales]);
    }
    public static function valida_fecha($fecha){
        $fecha  = Date::excelToDateTimeObject($fecha)->format('Y-m-d');
        $valores = explode('-', $fecha);
        $valor = $fecha;
        if(count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0])){
            if($fecha=='1970-01-01'){
                $valor =null;
                return [true, $valor];
            }
           if(intval($valores[0])<1990 ){
                return [false,''.$valor];
            }
            return [true, $valor];
        }
        return [false, ''.$valor];
    }
    public static function config_default(){
        $Configuracion = DB::select('select * from configuraciones where estado=1');
        $arr_conf=[];
        foreach($Configuracion as $conf ){
            $arr_conf[$conf->nombre]=$conf->valor;
        }
        return $arr_conf;
    }
    public function importar_eqt(Request $request)
    {
        try {
            $request->validate(['sucursal'=>'required']);
            $sucursal=$request->get('sucursal');
            $arr_conf= CargaController::config_default();
            $errors='';
            $null= null;
            $archivo = $request->file('archivo_excel');
            $rutaArchivo = $archivo->getRealPath();
            $spreadsheet = IOFactory::load($rutaArchivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $cont=1; 
            $fila = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if($cont>1){
                    $estado = $worksheet->getCell("A".$cont)->getValue();
                    if (is_numeric($estado) || $estado=='') {
                        $fila[$cont]['estado'] = $estado;
                    }else{
                        $errors.='<br>El estado debe ser un numero entero en la fila '.'A'.$cont .': '.$estado;
                    }
                    $descripcion = $worksheet->getCell("B".$cont)->getValue();
                    $fila[$cont]['descripcion'] = $descripcion;
                    $marca = $worksheet->getCell("C".$cont)->getValue();
                    $fila[$cont]['marca'] = $marca;
                    $vin = $worksheet->getCell("D".$cont)->getValue();
                    $fila[$cont]['vin'] = $vin;
                    $modelo = $worksheet->getCell("E".$cont)->getValue();
                    $fila[$cont]['modelo'] = $modelo;
                    $num_motor = $worksheet->getCell("F".$cont)->getValue();
                    $fila[$cont]['num_motor'] = $num_motor;
                    $color = $worksheet->getCell("G".$cont)->getValue();
                    $fila[$cont]['color'] = $color;
                    $tipo = $worksheet->getCell("H".$cont)->getValue();// default 0
                    if($tipo=='' || $tipo==0){
                        $tipo= $arr_conf['tipo_eqt_default'];
                    }
                    $fila[$cont]['tipo'] = $tipo;
                    $costo = intval($worksheet->getCell("I".$cont)->getValue());
                    $fila[$cont]['costo'] = $costo;
                    $nombre_provedor = $worksheet->getCell("J".$cont)->getValue();
                    $fila[$cont]['nombre_provedor'] = $nombre_provedor;
                    $num_factura = $worksheet->getCell("K".$cont)->getValue();
                    $fila[$cont]['num_factura'] = $num_factura;
                    $fecha_compra = $worksheet->getCell("L".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_compra);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_compra'] = $valor;
                    }else{
                        $errors.='<br>fecha de compra en formato incorrecto en la fila '.'L'.$cont .': '.$valor;
                    }
                    $tasa_depreciacion = $worksheet->getCell("M".$cont)->getValue();
                    $fila[$cont]['tasa_depreciacion'] = $tasa_depreciacion;
                    $vida_util = intval($worksheet->getCell("N".$cont)->getValue());
                    if (is_numeric($vida_util) || $vida_util=='') {
                        $fila[$cont]['vida_util'] = $vida_util;
                    }else{
                        $errors.='<br>los meses de vida util deben ser un numero entero en la fila '.'N'.$cont .': '.$vida_util;
                    }
                    $area_destinada = $worksheet->getCell("O".$cont)->getValue();// default 0
                    if($area_destinada=='' || $area_destinada==0){
                        $area_destinada= $arr_conf['area_default'];
                    }
                    $fila[$cont]['area_destinada'] = $area_destinada;
                    $puesto = $worksheet->getCell("P".$cont)->getValue();// default 0
                    if($puesto=='' || $puesto==0){
                        $puesto= $arr_conf['puesto_default'];
                    }
                    $fila[$cont]['puesto'] = $puesto;
                    $nombre_responsable = $worksheet->getCell("Q".$cont)->getValue();// default 0
                    if($nombre_responsable=='' || $nombre_responsable==0){
                        $nombre_responsable= $arr_conf['user_default'];
                    }
                    $fila[$cont]['nombre_responsable'] = $nombre_responsable;
                    $fecha_baja = $worksheet->getCell("R".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_baja);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_baja'] = $valor;
                    }else{
                        $errors.='<br>fecha de baja en formato incorrecto en la fila '.'R'.$cont .': '.$valor;
                    }
                    $motivo_baja = $worksheet->getCell("S".$cont)->getValue();
                    $fila[$cont]['motivo_baja'] = $motivo_baja;
                    $observaciones = $worksheet->getCell("T".$cont)->getValue();
                    $fila[$cont]['observaciones'] = $observaciones;
                    $garantia = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['garantia'] = $garantia;
                    $id_proveedor = $worksheet->getCell("V".$cont)->getValue();
                    $fila[$cont]['id_proveedor'] = $id_proveedor;
                    $numero_de_pedido = $worksheet->getCell("W".$cont)->getValue();
                    $fila[$cont]['numero_de_pedido'] = $numero_de_pedido;
                    $fecha_vida_util_inicio = $worksheet->getCell("X".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_vida_util_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_vida_util_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de vida en formato incorrecto en la fila '.'X'.$cont .': '.$valor;
                    }
                    $fecha_depreciacion_inicio = $worksheet->getCell("Y".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_depreciacion_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_depreciacion_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de depreciación en formato incorrecto en la fila '.'Y'.$cont .' : '.$valor;
                    }
                    $precio_venta = intval($worksheet->getCell("Z".$cont)->getValue());
                    $fila[$cont]['precio_venta'] = $precio_venta;
                    $estatus_depreciacion = $worksheet->getCell("AA".$cont)->getValue();// default 0
                    if($estatus_depreciacion=='' || $estatus_depreciacion==0){
                        $estatus_depreciacion= $arr_conf['depreciacion_default'];
                    }else{
                        if (is_numeric($estatus_depreciacion)) {
                        }else{
                            $errors.='<br>El estado depreciación debe ser un numero entero en la fila '.'AA'.$cont .': '.$estatus_depreciacion;
                        }
                    }
                    $fila[$cont]['estatus_depreciacion'] = $estatus_depreciacion;
                }
                $cont++;
            }
            if($errors!=''){
                $respuesta[0] = false;
                $respuesta[1] = $errors;
                return response()->json($respuesta);
            }else{
                $registros=0;
                $hoy = date("Y-m-d");
                foreach ($fila as $row) {
                    $Equipo= new Activo();
                    $Equipo->estado=$row['estado'];
                    $Equipo->descripcion=$row['descripcion'];
                    $Equipo->marca=$row['marca'];
                    $Equipo->serie=$row['vin'];
                    $Equipo->modelo=$row['modelo'];
                    $Equipo->num_motor=$row['num_motor'];
                    $Equipo->color=$row['color'];
                    $Equipo->tipo_id=$row['tipo'];
                    $Equipo->costo=$row['costo'];
                    $Equipo->nombre_provedor=$row['nombre_provedor'];
                    $Equipo->num_factura=$row['num_factura'];
                    $Equipo->fecha_compra=$row['fecha_compra'];
                    $Equipo->tasa_depreciacion=$row['tasa_depreciacion'];
                    $Equipo->vida_util=$row['vida_util'];
                    $Equipo->departamento_id=$row['area_destinada'];
                    $Equipo->puesto_id=$row['puesto'];
                    $Equipo->responsable_id=$row['nombre_responsable'];
                    $Equipo->fecha_baja=$row['fecha_baja'];
                    $Equipo->motivo_baja=$row['motivo_baja'];
                    $Equipo->observaciones=$row['observaciones'];
                    $Equipo->garantia=$row['garantia'];
                    $Equipo->id_proveedor=$row['id_proveedor'];
                    $Equipo->numero_de_pedido=$row['numero_de_pedido'];
                    $Equipo->fecha_vida_util_inicio=$row['fecha_vida_util_inicio'];
                    $Equipo->fecha_depreciacion_inicio=$row['fecha_depreciacion_inicio'];
                    $Equipo->precio_venta=$row['precio_venta'];
                    $Equipo->estatus_depreciacion=$row['estatus_depreciacion'];
                    $Equipo->masivo=$hoy;
                    $Equipo->sucursal_id=$sucursal;
                    $Equipo->created_user=auth()->user()->id;
                    $Equipo->save();
                    //buscamos las herramientas para definir el num de herramienta de la sucursal
                    $tipos=Tipo::where('id_giro',1)->where('sucursal_id',$sucursal)->get()->pluck('id_tipo');
                    $cont=Activo::where('sucursal_id',$sucursal)->whereIn('tipo_id',$tipos)->get()->count();
                    $id=$Equipo->id;
                    $Equipo->num_equipo='EQT-'.($cont+1);
		            $Equipo->update();
                    $registros++;
                }              
                    $respuesta[0] = true;
                    $respuesta[1] = $registros." Registros Ingresados";
                    return response()->json($respuesta);
            }
        }catch (\Exception $e) { 
            $respuesta[0] = false;
            $respuesta[1] = $e->getMessage();
            return response()->json($respuesta);
        }
    }
    public function index_eqc()
    {
        $respuesta='';
    	return view('inventario.Carga.index_eqc', ["Respuesta"=>$respuesta,"sucursales"=>$this->sucursales]);
    }
    public function importar_eqc(Request $request)
    {
        try {
            $request->validate(['sucursal'=>'required']);
            $sucursal=$request->get('sucursal');
            $arr_conf= CargaController::config_default();
            $errors='';
            $null= null;
            $archivo = $request->file('archivo_excel');
            $rutaArchivo = $archivo->getRealPath();
            $spreadsheet = IOFactory::load($rutaArchivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $cont=1; 
            $fila = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if($cont>1){
                    $estado = $worksheet->getCell("A".$cont)->getValue();
                    if (is_numeric($estado) || $estado=='') {
                        $fila[$cont]['estado'] = $estado;
                    }else{
                        $errors.='<br>El estado debe ser un numero entero en la fila '.'A'.$cont .': '.$estado;
                    }
                    $descripcion = $worksheet->getCell("B".$cont)->getValue();
                    $fila[$cont]['descripcion'] = $descripcion;
                    $marca = $worksheet->getCell("C".$cont)->getValue();
                    $fila[$cont]['marca'] = $marca;
                    $serie = $worksheet->getCell("D".$cont)->getValue();
                    $fila[$cont]['serie'] = $serie;
                    $modelo = $worksheet->getCell("E".$cont)->getValue();
                    $fila[$cont]['modelo'] = $modelo;
                    $color = $worksheet->getCell("F".$cont)->getValue();
                    $fila[$cont]['color'] = $color;
                    $tipo = $worksheet->getCell("G".$cont)->getValue();// default 0
                    if($tipo=='' || $tipo==0){
                        $tipo= $arr_conf['tipo_eqc_default'];
                    }
                    $fila[$cont]['tipo'] = $tipo;
                    $costo = intval($worksheet->getCell("H".$cont)->getValue());
                    $fila[$cont]['costo'] = $costo;
                    $nombre_provedor = $worksheet->getCell("I".$cont)->getValue();
                    $fila[$cont]['nombre_provedor'] = $nombre_provedor;
                    $num_factura = $worksheet->getCell("J".$cont)->getValue();
                    $fila[$cont]['num_factura'] = $num_factura;
                    $fecha_compra = $worksheet->getCell("K".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_compra);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_compra'] = $valor;
                    }else{
                        $errors.='<br>fecha de compra en formato incorrecto en la fila '.'K'.$cont .': '.$valor;
                    }
                    $tasa_depreciacion = $worksheet->getCell("L".$cont)->getValue();
                    $fila[$cont]['tasa_depreciacion'] = $tasa_depreciacion;
                    $vida_util = intval($worksheet->getCell("M".$cont)->getValue());
                    if (is_numeric($vida_util) || $vida_util=='') {
                        $fila[$cont]['vida_util'] = $vida_util;
                    }else{
                        $errors.='<br>los meses de vida util deben ser un numero entero en la fila '.'M'.$cont .': '.$vida_util;
                    }
                    $area_destinada = $worksheet->getCell("N".$cont)->getValue();// default 0
                    if($area_destinada=='' || $area_destinada==0){
                        $area_destinada= $arr_conf['area_default'];
                    }
                    $fila[$cont]['area_destinada'] = $area_destinada;
                    $puesto = $worksheet->getCell("O".$cont)->getValue();// default 0
                    if($puesto=='' || $puesto==0){
                        $puesto= $arr_conf['puesto_default'];
                    }
                    $fila[$cont]['puesto'] = $puesto;
                    $nombre_responsable = $worksheet->getCell("P".$cont)->getValue();// default 0
                    if($nombre_responsable=='' || $nombre_responsable==0){
                        $nombre_responsable= $arr_conf['user_default'];
                    }
                    $fila[$cont]['nombre_responsable'] = $nombre_responsable;
                    $fecha_baja = $worksheet->getCell("Q".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_baja);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_baja'] = $valor;
                    }else{
                        $errors.='<br>fecha de baja en formato incorrecto en la fila '.'Q'.$cont .': '.$valor;
                    }
                    $motivo_baja = $worksheet->getCell("R".$cont)->getValue();
                    $fila[$cont]['motivo_baja'] = $motivo_baja;
                    $observaciones = $worksheet->getCell("S".$cont)->getValue();
                    $fila[$cont]['observaciones'] = $observaciones;
                    $garantia = $worksheet->getCell("T".$cont)->getValue();
                    $fila[$cont]['garantia'] = $garantia;
                    $id_proveedor = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['id_proveedor'] = $id_proveedor;
                    $numero_de_pedido = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['numero_de_pedido'] = $numero_de_pedido;
                    $fecha_vida_util_inicio = $worksheet->getCell("W".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_vida_util_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_vida_util_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de vida en formato incorrecto en la fila '.'W'.$cont .': '.$valor;
                    }
                    $fecha_depreciacion_inicio = $worksheet->getCell("X".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_depreciacion_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_depreciacion_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de depreciación en formato incorrecto en la fila '.'X'.$cont .' : '.$valor;
                    }
                    $precio_venta = intval($worksheet->getCell("Y".$cont)->getValue());
                    $fila[$cont]['precio_venta'] = $precio_venta;
                    $estatus_depreciacion = $worksheet->getCell("Z".$cont)->getValue();// default 0
                    if($estatus_depreciacion=='' || $estatus_depreciacion==0){
                        $estatus_depreciacion= $arr_conf['depreciacion_default'];
                    }else{
                        if (is_numeric($estatus_depreciacion)) {
                        }else{
                            $errors.='<br>El estado depreciación debe ser un numero entero en la fila '.'Z'.$cont .': '.$estatus_depreciacion;
                        }
                    }
                    $fila[$cont]['estatus_depreciacion'] = $estatus_depreciacion;
                }
                $cont++;
            }
            if($errors!=''){
                $respuesta[0] = false;
                $respuesta[1] = $errors;
                return response()->json($respuesta);
            }else{
                $registros=0;
                $hoy = date("Y-m-d");
                foreach ($fila as $row) {
                    $Equipo= new Activo();
                    $Equipo->estado=$row['estado'];
                    $Equipo->descripcion=$row['descripcion'];
                    $Equipo->marca=$row['marca'];
                    $Equipo->serie=$row['serie'];
                    $Equipo->modelo=$row['modelo'];
                    $Equipo->color=$row['color'];
                    $Equipo->tipo_id=$row['tipo'];
                    $Equipo->costo=$row['costo'];
                    $Equipo->nombre_provedor=$row['nombre_provedor'];
                    $Equipo->num_factura=$row['num_factura'];
                    $Equipo->fecha_compra=$row['fecha_compra'];
                    $Equipo->tasa_depreciacion=$row['tasa_depreciacion'];
                    $Equipo->vida_util=$row['vida_util'];
                    $Equipo->departamento_id=$row['area_destinada'];
                    $Equipo->puesto_id=$row['puesto'];
                    $Equipo->responsable_id=$row['nombre_responsable'];
                    $Equipo->fecha_baja=$row['fecha_baja'];
                    $Equipo->motivo_baja=$row['motivo_baja'];
                    $Equipo->observaciones=$row['observaciones'];
                    $Equipo->garantia=$row['garantia'];
                    $Equipo->id_proveedor=$row['id_proveedor'];
                    $Equipo->numero_de_pedido=$row['numero_de_pedido'];
                    $Equipo->fecha_vida_util_inicio=$row['fecha_vida_util_inicio'];
                    $Equipo->fecha_depreciacion_inicio=$row['fecha_depreciacion_inicio'];
                    $Equipo->precio_venta=$row['precio_venta'];
                    $Equipo->estatus_depreciacion=$row['estatus_depreciacion'];
                    $Equipo->masivo=$hoy;
                    $Equipo->created_user=auth()->user()->id;
                    $Equipo->sucursal_id=$sucursal;
                    $Equipo->save();
                    $tipos=Tipo::where('id_giro',2)->where('sucursal_id',$sucursal)->get()->pluck('id_tipo');
                    $cont=Activo::where('sucursal_id',$sucursal)->whereIn('tipo_id',$tipos)->get()->count();
                    $id=$Equipo->id_equipo_computo;
                    $Equipo->num_equipo='EQC-'.($cont+1);
		            $Equipo->update();
                    $registros++;
                }              
                    $respuesta[0] = true;
                    $respuesta[1] = $registros." Registros Ingresados";
                    return response()->json($respuesta);
            }
        }catch (\Exception $e) { 
            $respuesta[0] = false;
            $respuesta[1] = $e->getMessage();
            return response()->json($respuesta);
        }
    }
    public function index_moe()
    {
    	$respuesta='';
    	return view('inventario.Carga.index_moe', ["Respuesta"=>$respuesta,"sucursales"=>$this->sucursales]);
    }
    public function importar_moe(Request $request)
    {
        try {
            $arr_conf= CargaController::config_default();
            $errors='';
            $null= null;
            $archivo = $request->file('archivo_excel');
            $rutaArchivo = $archivo->getRealPath();
            $spreadsheet = IOFactory::load($rutaArchivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $cont=1; 
            $fila = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if($cont>1){
                    $estado = $worksheet->getCell("A".$cont)->getValue();
                    if (is_numeric($estado) || $estado=='') {
                        $fila[$cont]['estado'] = $estado;
                    }else{
                        $errors.='<br>El estado debe ser un numero entero en la fila '.'A'.$cont .': '.$estado;
                    }
                    $descripcion = $worksheet->getCell("B".$cont)->getValue();
                    $fila[$cont]['descripcion'] = $descripcion;
                    $marca = $worksheet->getCell("C".$cont)->getValue();
                    $fila[$cont]['marca'] = $marca;
                    $serie = $worksheet->getCell("D".$cont)->getValue();
                    $fila[$cont]['serie'] = $serie;
                    $modelo = $worksheet->getCell("E".$cont)->getValue();
                    $fila[$cont]['modelo'] = $modelo;
                    $medida = $worksheet->getCell("F".$cont)->getValue();
                    $fila[$cont]['medida'] = $medida;
                    $color = $worksheet->getCell("G".$cont)->getValue();
                    $fila[$cont]['color'] = $color;
                    $tipo = $worksheet->getCell("H".$cont)->getValue();// default 0
                    if($tipo=='' || $tipo==0){
                        $tipo= $arr_conf['tipo_moe_default'];
                    }
                    $fila[$cont]['tipo'] = $tipo;
                    $costo = intval($worksheet->getCell("I".$cont)->getValue());
                    $fila[$cont]['costo'] = $costo;
                    $nombre_provedor = $worksheet->getCell("J".$cont)->getValue();
                    $fila[$cont]['nombre_provedor'] = $nombre_provedor;
                    $num_factura = $worksheet->getCell("K".$cont)->getValue();
                    $fila[$cont]['num_factura'] = $num_factura;
                    $fecha_compra = $worksheet->getCell("L".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_compra);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_compra'] = $valor;
                    }else{
                        $errors.='<br> fecha de compra en formato incorrecto en la fila '.'L'.$cont .': '.$valor;
                    }
                    $tasa_depreciacion = $worksheet->getCell("M".$cont)->getValue();
                    $fila[$cont]['tasa_depreciacion'] = $tasa_depreciacion;
                    $vida_util = intval($worksheet->getCell("N".$cont)->getValue());
                    if (is_numeric($vida_util) || $vida_util=='') {
                        $fila[$cont]['vida_util'] = $vida_util;
                    }else{
                        $errors.='<br> los meses de vida util deben ser un numero entero en la fila '.'N'.$cont .': '.$vida_util;
                    }
                    $area_destinada = $worksheet->getCell("O".$cont)->getValue();// default 0
                    if($area_destinada=='' || $area_destinada==0){
                        $area_destinada= $arr_conf['area_default'];
                    }
                    $fila[$cont]['area_destinada'] = $area_destinada;
                    $puesto = $worksheet->getCell("P".$cont)->getValue();// default 0
                    if($puesto=='' || $puesto==0){
                        $puesto= $arr_conf['puesto_default'];
                    }
                    $fila[$cont]['puesto'] = $puesto;
                    $nombre_responsable = $worksheet->getCell("Q".$cont)->getValue();// default 0
                    if($nombre_responsable=='' || $nombre_responsable==0){
                        $nombre_responsable= $arr_conf['user_default'];
                    }
                    $fila[$cont]['nombre_responsable'] = $nombre_responsable;
                    $fecha_baja = $worksheet->getCell("R".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_baja);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_baja'] = $valor;
                    }else{
                        $errors.='<br>fecha de baja en formato incorrecto en la fila '.'R'.$cont .': '.$valor;
                    }
                    $motivo_baja = $worksheet->getCell("S".$cont)->getValue();
                    $fila[$cont]['motivo_baja'] = $motivo_baja;
                    $observaciones = $worksheet->getCell("T".$cont)->getValue();
                    $fila[$cont]['observaciones'] = $observaciones;
                    $garantia = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['garantia'] = $garantia;
                    $id_proveedor = $worksheet->getCell("V".$cont)->getValue();
                    $fila[$cont]['id_proveedor'] = $id_proveedor;
                    $numero_de_pedido = $worksheet->getCell("W".$cont)->getValue();
                    $fila[$cont]['numero_de_pedido'] = $numero_de_pedido;
                    $fecha_vida_util_inicio = $worksheet->getCell("X".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_vida_util_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_vida_util_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de vida en formato incorrecto en la fila '.'X'.$cont .': '.$valor;
                    }
                    $fecha_depreciacion_inicio = $worksheet->getCell("Y".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_depreciacion_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_depreciacion_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de depreciación en formato incorrecto en la fila '.'Y'.$cont .' : '.$valor;
                    }
                    $precio_venta = intval($worksheet->getCell("Z".$cont)->getValue());
                    $fila[$cont]['precio_venta'] = $precio_venta;
                    $estatus_depreciacion = $worksheet->getCell("AA".$cont)->getValue();// default 0
                    if($estatus_depreciacion=='' || $estatus_depreciacion==0){
                        $estatus_depreciacion= $arr_conf['depreciacion_default'];
                    }else{
                        if (is_numeric($estatus_depreciacion)) {
                        }else{
                            $errors.='<br>El estado depreciación debe ser un numero entero en la fila '.'AA'.$cont .': '.$estatus_depreciacion;
                        }
                    }
                    $fila[$cont]['estatus_depreciacion'] = $estatus_depreciacion;
                    
                }
                $cont++;
            }
            if($errors!=''){
                $respuesta[0] = false;
                $respuesta[1] = $errors;
                return response()->json($respuesta);
            }else{
                $registros=0;
                $hoy = date("Y-m-d");
                foreach ($fila as $row) {
                    $Equipo= new MobiliarioEquipo;
                    $Equipo->estado=$row['estado'];
                    $Equipo->descripcion=$row['descripcion'];
                    $Equipo->marca=$row['marca'];
                    $Equipo->serie=$row['serie'];
                    $Equipo->modelo=$row['modelo'];
                    $Equipo->medida=$row['medida'];
                    $Equipo->color=$row['color'];
                    $Equipo->tipo=$row['tipo'];
                    $Equipo->costo=$row['costo'];
                    $Equipo->nombre_provedor=$row['nombre_provedor'];
                    $Equipo->num_factura=$row['num_factura'];
                    $Equipo->fecha_compra=$row['fecha_compra'];
                    $Equipo->tasa_depreciacion=$row['tasa_depreciacion'];
                    $Equipo->vida_util=$row['vida_util'];
                    $Equipo->area_destinada=$row['area_destinada'];
                    $Equipo->puesto=$row['puesto'];
                    $Equipo->nombre_responsable=$row['nombre_responsable'];
                    $Equipo->fecha_baja=$row['fecha_baja'];
                    $Equipo->motivo_baja=$row['motivo_baja'];
                    $Equipo->observaciones=$row['observaciones'];
                    $Equipo->garantia=$row['garantia'];
                    $Equipo->id_proveedor=$row['id_proveedor'];
                    $Equipo->numero_de_pedido=$row['numero_de_pedido'];
                    $Equipo->fecha_vida_util_inicio=$row['fecha_vida_util_inicio'];
                    $Equipo->fecha_depreciacion_inicio=$row['fecha_depreciacion_inicio'];
                    $Equipo->precio_venta=$row['precio_venta'];
                    $Equipo->estatus_depreciacion=$row['estatus_depreciacion'];
                    $Equipo->masivo=$hoy;
                    $Equipo->created_user=auth()->user()->id;
                    $Equipo->save();
                    $id=$Equipo->id_equipo_mobiliario;
                    $Equipo->num_equipo='MOE-'.$Equipo->id_equipo_mobiliario;
		            $Equipo->update();
                    $registros++;
                }
                    $respuesta[0] = true;
                    $respuesta[1] = $registros." Registros Ingresados";
                    return response()->json($respuesta);
            }
        }catch (\Exception $e) { 
            $respuesta[0] = false;
            $respuesta[1] = $e->getMessage();
            return response()->json($respuesta);
        }
    }
    public function index_mae()
    {
    	$respuesta='';
    	return view('inventario.Carga.index_mae', ["Respuesta"=>$respuesta]);
    }
    public function importar_mae(Request $request)
    {
        try {
            $arr_conf= CargaController::config_default();
            $errors='';
            $null= null;
            $archivo = $request->file('archivo_excel');
            $rutaArchivo = $archivo->getRealPath();
            $spreadsheet = IOFactory::load($rutaArchivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $cont=1; 
            $fila = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if($cont>1){
                    $estado = $worksheet->getCell("A".$cont)->getValue();
                    if (is_numeric($estado) || $estado=='') {
                        $fila[$cont]['estado'] = $estado;
                    }else{
                        $errors.='<br>El estado debe ser un numero entero en la fila '.'A'.$cont .': '.$estado;
                    }
                    $descripcion = $worksheet->getCell("B".$cont)->getValue();
                    $fila[$cont]['descripcion'] = $descripcion;
                    $marca = $worksheet->getCell("C".$cont)->getValue();
                    $fila[$cont]['marca'] = $marca;
                    $serie = $worksheet->getCell("D".$cont)->getValue();
                    $fila[$cont]['serie'] = $serie;
                    $modelo = $worksheet->getCell("E".$cont)->getValue();
                    $fila[$cont]['modelo'] = $modelo;
                    $color = $worksheet->getCell("F".$cont)->getValue();
                    $fila[$cont]['color'] = $color;
                    $tipo = $worksheet->getCell("G".$cont)->getValue();// default 0
                    if($tipo=='' || $tipo==0){
                        $tipo= $arr_conf['tipo_mae_default'];
                    }
                    $fila[$cont]['tipo'] = $tipo;
                    $costo = intval($worksheet->getCell("H".$cont)->getValue());
                    $fila[$cont]['costo'] = $costo;
                    $nombre_provedor = $worksheet->getCell("I".$cont)->getValue();
                    $fila[$cont]['nombre_provedor'] = $nombre_provedor;
                    $num_factura = $worksheet->getCell("J".$cont)->getValue();
                    $fila[$cont]['num_factura'] = $num_factura;
                    $fecha_compra = $worksheet->getCell("K".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_compra);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_compra'] = $valor;
                    }else{
                        $errors.='<br>fecha de compra en formato incorrecto en la fila '.'K'.$cont .': '.$valor;
                    }
                    $tasa_depreciacion = $worksheet->getCell("L".$cont)->getValue();
                    $fila[$cont]['tasa_depreciacion'] = $tasa_depreciacion;
                    $vida_util = intval($worksheet->getCell("M".$cont)->getValue());
                    if (is_numeric($vida_util) || $vida_util=='') {
                        $fila[$cont]['vida_util'] = $vida_util;
                    }else{
                        $errors.='<br>los meses de vida util deben ser un numero entero en la fila '.'M'.$cont .': '.$vida_util;
                    }
                    $area_destinada = $worksheet->getCell("N".$cont)->getValue();// default 0
                    if($area_destinada=='' || $area_destinada==0){
                        $area_destinada= $arr_conf['area_default'];
                    }
                    $fila[$cont]['area_destinada'] = $area_destinada;
                    $puesto = $worksheet->getCell("O".$cont)->getValue();// default 0
                    if($puesto=='' || $puesto==0){
                        $puesto= $arr_conf['puesto_default'];
                    }
                    $fila[$cont]['puesto'] = $puesto;
                    $nombre_responsable = $worksheet->getCell("P".$cont)->getValue();// default 0
                    if($nombre_responsable=='' || $nombre_responsable==0){
                        $nombre_responsable= $arr_conf['user_default'];
                    }
                    $fila[$cont]['nombre_responsable'] = $nombre_responsable;
                    $fecha_baja = $worksheet->getCell("Q".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_baja);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_baja'] = $valor;
                    }else{
                        $errors.='<br>fecha de baja en formato incorrecto en la fila '.'Q'.$cont .': '.$valor;
                    }
                    $motivo_baja = $worksheet->getCell("R".$cont)->getValue();
                    $fila[$cont]['motivo_baja'] = $motivo_baja;
                    $observaciones = $worksheet->getCell("S".$cont)->getValue();
                    $fila[$cont]['observaciones'] = $observaciones;
                    $garantia = $worksheet->getCell("T".$cont)->getValue();
                    $fila[$cont]['garantia'] = $garantia;
                    $id_proveedor = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['id_proveedor'] = $id_proveedor;
                    $numero_de_pedido = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['numero_de_pedido'] = $numero_de_pedido;
                    $fecha_vida_util_inicio = $worksheet->getCell("W".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_vida_util_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_vida_util_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de vida en formato incorrecto en la fila '.'W'.$cont .': '.$valor;
                    }
                    $fecha_depreciacion_inicio = $worksheet->getCell("X".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_depreciacion_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_depreciacion_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de depreciación en formato incorrecto en la fila '.'X'.$cont .' : '.$valor;
                    }
                    $precio_venta = intval($worksheet->getCell("Y".$cont)->getValue());
                    $fila[$cont]['precio_venta'] = $precio_venta;
                    $estatus_depreciacion = $worksheet->getCell("Z".$cont)->getValue();// default 0
                    if($estatus_depreciacion=='' || $estatus_depreciacion==0){
                        $estatus_depreciacion= $arr_conf['depreciacion_default'];
                    }else{
                        if (is_numeric($estatus_depreciacion)) {
                        }else{
                            $errors.='<br>El estado depreciación debe ser un numero entero en la fila '.'Z'.$cont .': '.$estatus_depreciacion;
                        }
                    }
                    $fila[$cont]['estatus_depreciacion'] = $estatus_depreciacion;
                }
                $cont++;
            }
            if($errors!=''){
                $respuesta[0] = false;
                $respuesta[1] = $errors;
                return response()->json($respuesta);
            }else{
                $registros=0;
                $hoy = date("Y-m-d");
                foreach ($fila as $row) {
                    $Equipo= new MaquinariaEquipo;
                    $Equipo->estado=$row['estado'];
                    $Equipo->descripcion=$row['descripcion'];
                    $Equipo->marca=$row['marca'];
                    $Equipo->serie=$row['serie'];
                    $Equipo->modelo=$row['modelo'];
                    $Equipo->color=$row['color'];
                    $Equipo->tipo=$row['tipo'];
                    $Equipo->costo=$row['costo'];
                    $Equipo->nombre_provedor=$row['nombre_provedor'];
                    $Equipo->num_factura=$row['num_factura'];
                    $Equipo->fecha_compra=$row['fecha_compra'];
                    $Equipo->tasa_depreciacion=$row['tasa_depreciacion'];
                    $Equipo->vida_util=$row['vida_util'];
                    $Equipo->area_destinada=$row['area_destinada'];
                    $Equipo->puesto=$row['puesto'];
                    $Equipo->nombre_responsable=$row['nombre_responsable'];
                    $Equipo->fecha_baja=$row['fecha_baja'];
                    $Equipo->motivo_baja=$row['motivo_baja'];
                    $Equipo->observaciones=$row['observaciones'];
                    $Equipo->garantia=$row['garantia'];
                    $Equipo->id_proveedor=$row['id_proveedor'];
                    $Equipo->numero_de_pedido=$row['numero_de_pedido'];
                    $Equipo->fecha_vida_util_inicio=$row['fecha_vida_util_inicio'];
                    $Equipo->fecha_depreciacion_inicio=$row['fecha_depreciacion_inicio'];
                    $Equipo->precio_venta=$row['precio_venta'];
                    $Equipo->estatus_depreciacion=$row['estatus_depreciacion'];
                    $Equipo->masivo=$hoy;
                    $Equipo->created_user=auth()->user()->id;
                    $Equipo->save();
                    $id=$Equipo->id_equipo_maquinaria;
                    $Equipo->num_equipo='MAE-'.$Equipo->id_equipo_maquinaria;
		            $Equipo->update();
                    $registros++;
                }              
                    $respuesta[0] = true;
                    $respuesta[1] = $registros." Registros Ingresados";
                    return response()->json($respuesta);
            }
        }catch (\Exception $e) { 
            $respuesta[0] = false;
            $respuesta[1] = $e->getMessage();
            return response()->json($respuesta);
        }
    }
    public function index_her()
    {
    	$respuesta='';
    	return view('inventario.Carga.index_her', ["Respuesta"=>$respuesta,"sucursales"=>$this->sucursales]);
    }
    public function importar_her(Request $request)
    {
        try {
            $request->validate(['sucursal'=>'required']);
            $sucursal=$request->get('sucursal');
            $arr_conf= CargaController::config_default();
            $errors='';
            $null= null;
            $archivo = $request->file('archivo_excel');
            $rutaArchivo = $archivo->getRealPath();
            $spreadsheet = IOFactory::load($rutaArchivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $cont=1; 
            $fila = [];
             foreach ($worksheet->getRowIterator() as $row) {
                if($cont>1){
                    $estado = $worksheet->getCell("A".$cont)->getValue();
                    if (is_numeric($estado) || $estado=='') {
                        $fila[$cont]['estado'] = $estado;
                    }else{
                        $errors.='<br>El estado debe ser un numero entero en la fila '.'A'.$cont .': '.$estado;
                    }
                    $descripcion = $worksheet->getCell("B".$cont)->getValue();
                    $fila[$cont]['descripcion'] = $descripcion;
                    $marca = $worksheet->getCell("C".$cont)->getValue();
                    $fila[$cont]['marca'] = $marca;
                    $codigo = $worksheet->getCell("D".$cont)->getValue();
                    $fila[$cont]['codigo'] = $codigo;
                    $modelo = $worksheet->getCell("E".$cont)->getValue();
                    $fila[$cont]['modelo'] = $modelo;
                    $medida = $worksheet->getCell("F".$cont)->getValue();
                    $fila[$cont]['medida'] = $medida;
                    $color = $worksheet->getCell("G".$cont)->getValue();
                    $fila[$cont]['color'] = $color;
                    $tipo = $worksheet->getCell("H".$cont)->getValue();// default 0
                    if($tipo=='' || $tipo==0){
                        $tipo= $arr_conf['tipo_her_default'];
                    }
                    $fila[$cont]['tipo'] = $tipo;
                    $costo = intval($worksheet->getCell("I".$cont)->getValue());
                    $fila[$cont]['costo'] = $costo;
                    $nombre_provedor = $worksheet->getCell("J".$cont)->getValue();
                    $fila[$cont]['nombre_provedor'] = $nombre_provedor;
                    $num_factura = $worksheet->getCell("K".$cont)->getValue();
                    $fila[$cont]['num_factura'] = $num_factura;
                    $fecha_compra = $worksheet->getCell("L".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_compra);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_compra'] = $valor;
                    }else{
                        $errors.='<br> fecha de compra en formato incorrecto en la fila '.'L'.$cont .': '.$valor;
                    }
                    $tasa_depreciacion = $worksheet->getCell("M".$cont)->getValue();
                    $fila[$cont]['tasa_depreciacion'] = $tasa_depreciacion;
                    $vida_util = intval($worksheet->getCell("N".$cont)->getValue());
                    if (is_numeric($vida_util) || $vida_util=='') {
                        $fila[$cont]['vida_util'] = $vida_util;
                    }else{
                        $errors.='<br> los meses de vida util deben ser un numero entero en la fila '.'N'.$cont .': '.$vida_util;
                    }
                    $area_destinada = $worksheet->getCell("O".$cont)->getValue();// default 0
                    if($area_destinada=='' || $area_destinada==0){
                        $area_destinada= $arr_conf['area_default'];
                    }
                    $fila[$cont]['area_destinada'] = $area_destinada;
                    $puesto = $worksheet->getCell("P".$cont)->getValue();// default 0
                    if($puesto=='' || $puesto==0){
                        $puesto= $arr_conf['puesto_default'];
                    }
                    $fila[$cont]['puesto'] = $puesto;
                    $nombre_responsable = $worksheet->getCell("Q".$cont)->getValue();// default 0
                    if($nombre_responsable=='' || $nombre_responsable==0){
                        $nombre_responsable= $arr_conf['user_default'];
                    }
                    $fila[$cont]['nombre_responsable'] = $nombre_responsable;
                    $fecha_baja = $worksheet->getCell("R".$cont)->getValue();
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_baja);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_baja'] = $valor;
                    }else{
                        $errors.='<br>fecha de baja en formato incorrecto en la fila '.'R'.$cont .': '.$valor;
                    }
                    $motivo_baja = $worksheet->getCell("S".$cont)->getValue();
                    $fila[$cont]['motivo_baja'] = $motivo_baja;
                    $observaciones = $worksheet->getCell("T".$cont)->getValue();
                    $fila[$cont]['observaciones'] = $observaciones;
                    $garantia = $worksheet->getCell("U".$cont)->getValue();
                    $fila[$cont]['garantia'] = $garantia;
                    $id_proveedor = $worksheet->getCell("V".$cont)->getValue();
                    $fila[$cont]['id_proveedor'] = $id_proveedor;
                    $numero_de_pedido = $worksheet->getCell("W".$cont)->getValue();
                    $fila[$cont]['numero_de_pedido'] = $numero_de_pedido;
                    $fecha_vida_util_inicio = $worksheet->getCell("X".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_vida_util_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_vida_util_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de vida en formato incorrecto en la fila '.'X'.$cont .': '.$valor;
                    }
                    $fecha_depreciacion_inicio = $worksheet->getCell("Y".$cont)->getValue();//fecha
                    list($fecha_response, $valor) = CargaController::valida_fecha($fecha_depreciacion_inicio);
                    if($fecha_response){ 
                        $fila[$cont]['fecha_depreciacion_inicio'] = $valor;
                    }else{
                        $errors.='<br>fecha de inicio de depreciación en formato incorrecto en la fila '.'Y'.$cont .' : '.$valor;
                    }
                    $precio_venta = intval($worksheet->getCell("Z".$cont)->getValue());
                    $fila[$cont]['precio_venta'] = $precio_venta;
                    $estatus_depreciacion = $worksheet->getCell("AA".$cont)->getValue();// default 0
                    if($estatus_depreciacion=='' || $estatus_depreciacion==0){
                        $estatus_depreciacion= $arr_conf['depreciacion_default'];
                    }else{
                        if (is_numeric($estatus_depreciacion)) {
                        }else{
                            $errors.='<br>El estado depreciación debe ser un numero entero en la fila '.'AA'.$cont .': '.$estatus_depreciacion;
                        }
                    }
                    $fila[$cont]['estatus_depreciacion'] = $estatus_depreciacion;
                    
                }
                $cont++;
            }
            if($errors!=''){
                $respuesta[0] = false;
                $respuesta[1] = $errors;
                return response()->json($respuesta);
            }else{
                $registros=0;
                $hoy = date("Y-m-d");
                foreach ($fila as $row) {
                    $Equipo= new Activo();
                    $Equipo->estado=$row['estado'];
                    $Equipo->descripcion=$row['descripcion'];
                    $Equipo->marca=$row['marca'];
                    $Equipo->serie=$row['codigo'];
                    $Equipo->modelo=$row['modelo'];
                    $Equipo->medida=$row['medida'];
                    $Equipo->color=$row['color'];
                    $Equipo->tipo_id=$row['tipo'];
                    $Equipo->costo=$row['costo'];
                    $Equipo->nombre_provedor=$row['nombre_provedor'];
                    $Equipo->num_factura=$row['num_factura'];
                    $Equipo->fecha_compra=$row['fecha_compra'];
                    $Equipo->tasa_depreciacion=$row['tasa_depreciacion'];
                    $Equipo->vida_util=$row['vida_util'];
                    $Equipo->departamento_id=$row['area_destinada'];
                    $Equipo->puesto_id=$row['puesto'];
                    $Equipo->responsable_id=$row['nombre_responsable'];
                    $Equipo->fecha_baja=$row['fecha_baja'];
                    $Equipo->motivo_baja=$row['motivo_baja'];
                    $Equipo->observaciones=$row['observaciones'];
                    $Equipo->garantia=$row['garantia'];
                    $Equipo->id_proveedor=$row['id_proveedor'];
                    $Equipo->numero_de_pedido=$row['numero_de_pedido'];
                    $Equipo->fecha_vida_util_inicio=$row['fecha_vida_util_inicio'];
                    $Equipo->fecha_depreciacion_inicio=$row['fecha_depreciacion_inicio'];
                    $Equipo->precio_venta=$row['precio_venta'];
                    $Equipo->estatus_depreciacion=$row['estatus_depreciacion'];
                    $Equipo->masivo=$hoy;
                    $Equipo->created_user=auth()->user()->id;
                    $Equipo->sucursal_id=$sucursal;
                    $Equipo->save();

                    //buscamos las herramientas para definir el num de herramienta de la sucursal
                    $tipos=Tipo::where('id_giro',5)->where('sucursal_id',$sucursal)->get()->pluck('id_tipo');
                    $cont=Activo::where('sucursal_id',$sucursal)->whereIn('tipo_id',$tipos)->get()->count();
                    //$id=$Equipo->id_equipo_herramienta;
                    $Equipo->num_equipo='HER-'.($cont+1);
		            $Equipo->update();
                    $registros++;
                }              
                    $respuesta[0] = true;
                    $respuesta[1] = $registros." Registros Ingresados";
                    return response()->json($respuesta);
            }
        }catch (\Exception $e) { 
            $respuesta[0] = false;
            $respuesta[1] = $e->getMessage();
            return response()->json($respuesta);
        }
    }
}
