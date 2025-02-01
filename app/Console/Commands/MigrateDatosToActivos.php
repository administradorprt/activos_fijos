<?php

namespace App\Console\Commands;

use App\Models\Activo;
use App\Models\Imagen;
use App\Models\Tipo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateDatosToActivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-datos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar datos de todas las tablas a la tabla de activos';

    /**
     * Guarda el valor en la tabla de activos
     * @param Collection $data 
     */
    public function saveActivo($data,$clase,$oldId){
        $activo=new Activo();
        $activo->departamento_id=$data->area_destinada;
        $activo->tipo_id=$data->tipo;
        $activo->sucursal_id=1;
        $activo->responsable_id=$data->nombre_responsable;
        $activo->created_user=$data->created_user;
        $activo->puesto_id=$data->puesto;
        $activo->estatus_depreciacion=$data->estatus_depreciacion;
        $activo->estado=$data->estado;
        $activo->num_equipo=$data->num_equipo;
        $activo->descripcion=$data->descripcion;
        $activo->marca=$data->marca;
        if(isset($data->codigo)){
            $activo->serie=$data->codigo;
        }
        if(isset($data->vin)){
            $activo->serie=$data->vin;
        }
        if(isset($data->serie)){
            $activo->serie=$data->serie;
        }
        $activo->modelo=$data->modelo;
        $activo->color=$data->color;
        $activo->masivo=$data->masivo;
        $activo->costo=$data->costo;
        $activo->nombre_provedor=$data->nombre_provedor;
        $activo->num_factura=$data->num_factura;
        $activo->fecha_compra=$data->fecha_compra;
        $activo->xml=$data->xml;
        $activo->pdf=$data->pdf;
        $activo->tasa_depreciacion=$data->tasa_depreciacion;
        $activo->vida_util=$data->vida_util;
        $activo->carta_responsiva=$data->carta_responsiva;
        $activo->fecha_baja=$data->fecha_baja;
        $activo->motivo_baja=$data->motivo_baja;
        $activo->observaciones=$data->observaciones;
        $activo->garantia=$data->garantia;
        $activo->id_proveedor=$data->id_proveedor;
        $activo->numero_de_pedido=$data->numero_de_pedido;
        $activo->fecha_vida_util_inicio=$data->fecha_vida_util_inicio;
        $activo->fecha_depreciacion_inicio=$data->fecha_depreciacion_inicio;
        $activo->precio_venta=$data->precio_venta;
        if(isset($data->num_motor)){
            $activo->num_motor=$data->num_motor;
        }
        if(isset($data->medida)){
            $activo->medida=$data->medida;
        }
        $activo->save();
        $this->saveImage($oldId,$activo->id,$clase);
    }
    /**
     * Guarda la imagen en la tabla de imagenes
     * @param int $idActivo id del activo
     * @param string $clase tipo de activo a buscar en la tabla de imagenes q
     */
    public function saveImage($oldId,$newId,$clase){
        try {
            $imgsSearch=DB::connection('activos2')->table('imagenes')->where([['id_equipo',$oldId],['id_area',$clase]])->get();
            foreach($imgsSearch as $img){
                $imagen=new Imagen();
                $imagen->activo_id=$newId;
                $imagen->imagen=$img->imagen;
                $imagen->estado=$img->estado;
                $imagen->save();
            }
        } catch (\Throwable $th) {
            Log::error('Exception',['clase'=>$clase,'oldId'=>$oldId]);
        }
    }
    public function saveTipos(){
        $tipos=DB::connection('activos2')->table('tipos')->get();
        foreach($tipos as $tipo){
            $newTipo=new Tipo();
            $newTipo->id_tipo=$tipo->id_tipo;
            $newTipo->id_giro=$tipo->id_giro;
            $newTipo->sucursal_id=1;
            $newTipo->nombre=$tipo->nombre;
            $newTipo->estado=$tipo->estado;
            $newTipo->save();
        }
    }
    /** 
     * La BD a la que apunta la conexion 'activos2' debe cambiarse para estar migrando los datos por agencia.
    */
    public function handle()
    {
        $herramientas=DB::connection('activos2')->table('herramientas')->get();//db:resguardocontraloria
        $quCompMont=DB::connection('activos2')->table('equipo_computo')->get();
        $mobiliario=DB::connection('activos2')->table('mobiliario_y_equipo')->get();
        $maquinaria=DB::connection('activos2')->table('maquinaria_y_equipo')->get();
        $transporte=DB::connection('activos2')->table('equipo_transporte')->get();

        //Guarda los datos en la tabla de activos
        //mover saveimagen dentro de guardar activo
        foreach($herramientas as $herramienta){
            $this->saveActivo($herramienta,5,$herramienta->id_equipo_herramienta);
        }
        foreach($quCompMont as $quComp){
            $this->saveActivo($quComp,2,$quComp->id_equipo_computo);
        }
        foreach($mobiliario as $mobil){
            $this->saveActivo($mobil,3,$mobil->id_equipo_mobiliario);
        }
        foreach($maquinaria as $maqui){
            $this->saveActivo($maqui,4,$maqui->id_equipo_maquinaria);
        }
        foreach($transporte as $trans){
            $this->saveActivo($trans,1,$trans->id_equipo_transporte);
        }

        //Guarda los tipos en la tabla de tipos
        $this->saveTipos();
    }
}
