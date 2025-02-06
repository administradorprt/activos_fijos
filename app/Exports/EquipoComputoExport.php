<?php

namespace App\Exports;

use App\Models\Activo;
use App\Models\EquipoComputo;
use App\Models\Tipo;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EquipoComputoExport implements FromQuery, ShouldAutoSize, WithMapping,WithHeadings,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $sucursales;
    public function __construct() {
        $this->sucursales=Auth::user()->empleado->sucursales;
    }

    public function forState(int $state)
    {
        $this->state = $state;
        
        return $this;
    }
    public function query()
    {
        $tipos=Tipo::where('id_giro',2)->whereIn('sucursal_id',$this->sucursales->pluck('id_sucursal'))->get()->pluck('id_tipo');
        switch ($this->state) {
            case '1':
                return Activo::query()->whereIn('tipo_id',$tipos);
            break;
            case '2':
                return Activo::query()->where("estado","=",0)->whereIn('tipo_id',$tipos);
            break;
            case '3':
                return Activo::query()->where("estado","=",1)->whereIn('tipo_id',$tipos);
            break;
        }
    }   
    public function map($invoice): array
    {
        return [
            $invoice->num_equipo,
            $invoice->descripcion,
            $invoice->estado ? 'activo':'inactivo',
            $invoice->marca,
            $invoice->serie,
            $invoice->modelo,
            $invoice->color,
            $invoice->tipos->nombre,
            $invoice->costo,
            $invoice->nombre_provedor,
            $invoice->id_proveedor,
            $invoice->num_factura,
            Date::PHPToExcel($invoice->fecha_compra),//$invoice->fecha_compra,
            $invoice->tasa_depreciacion,
            $invoice->vida_util,
            isset($invoice->empleado)?$invoice->empleado->nombres." ".$invoice->empleado->apellido_p:'Sin asignar',
            $invoice->area->nombre??'Sin área',
            $invoice->puestos->nombre??'Sin puesto',
            Date::PHPToExcel($invoice->fecha_baja),
            $invoice->motivo_baja,
            $invoice->observaciones,
            $invoice->garantia,
            $invoice->numero_de_pedido,
            Date::PHPToExcel($invoice->fecha_vida_util_inicio),
            Date::PHPToExcel($invoice->fecha_depreciacion_inicio),
            $invoice->precio_venta,
            $invoice->estadodepreciacion->nombre,
        ];
    }
    public function headings(): array
    {
        return [
            'id',
            'descripcion',
            'estado',
            'marca',
            'serie',
            'modelo',
            'color',
            'tipo',
            'costo',
            'proveedor',
            'id proveedor',
            'numero de factura',
            'fecha de compra',
            'tasa de depreciacion',
            'vida util',
            'responsable',
            'area',
            'puesto',
            'fecha de baja',
            'motivo de baja',
            'observaciones',
            'garantia',
            'n. pedido',
            'inicio vida util',
            'inicio depreciacion',
            'precio venta',
            'estado depreciacion',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER, 
            'M' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'S' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'X' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'Y' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
        ];
    }
}
