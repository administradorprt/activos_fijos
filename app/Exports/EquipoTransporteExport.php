<?php

namespace App\Exports;

use App\Models\EquipoTransporte;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
class EquipoTransporteExport implements FromQuery, ShouldAutoSize, WithMapping,WithHeadings,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function view(): View
    {
        return view('inventario.EquipoTransporte.export', [
            'equipos' => EquipoTransporte::all(),
           
        ]);
    }*/
    use Exportable;

    public function forState(int $state)
    {
        $this->state = $state;
        
        return $this;
    }
    public function query()
    {
        switch ($this->state) {
            case '1':
                return EquipoTransporte::query();
                break;
            
            case '2':
                return EquipoTransporte::query()->where("estado","=",0);
                break;

            case '3':
                return EquipoTransporte::query()->where("estado","=",1);
                break;
        }
        return EquipoTransporte::query();
    }   
    public function map($invoice): array
    {
        return [
            $invoice->num_equipo,
            $invoice->descripcion,
            $invoice->estado ? 'activo':'inactivo',
            $invoice->marca,
            $invoice->vin,
            $invoice->modelo,
            $invoice->num_motor,
            $invoice->color,
            $invoice->tipos->nombre,
            $invoice->costo,
            $invoice->nombre_provedor,
            $invoice->id_proveedor,
            $invoice->num_factura,
            Date::PHPToExcel($invoice->fecha_compra),//$invoice->fecha_compra,
            $invoice->tasa_depreciacion,
            $invoice->vida_util,
            $invoice->empleado->nombre." ".$invoice->empleado->apellido_paterno,
            $invoice->area->nombre,
            $invoice->puestos->nombre??'',
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
            'vin',
            'modelo',
            'motor',
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
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'T' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'Y' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'Z' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
        ];
    }
}