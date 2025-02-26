<?php

namespace App\Exports;

use App\Models\Activo;
use App\Models\Prestamo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrestamosExport implements FromQuery,WithMapping,WithHeadings,ShouldAutoSize,WithEvents,WithStyles,WithColumnWidths
{
    use RegistersEventListeners;
    /**
    * @param $giro int
    */
    private $giro,$fecha_in,$fecha_fin,$sucursales;
    public function __construct($giro,$fecha_in,$fecha_fin) {
        $this->giro = $giro;
        $this->fecha_in = $fecha_in;
        $this->fecha_fin = $fecha_fin;
        $this->sucursales=Auth::user()->empleado->sucursales;
    }
    public function headings(): array
    {
        return ['Sucursal','Num.Equipo','Descripción','Usuario','Puesto','Fecha de prestamo','Fecha de devolución','Tiempo de prestamo','comentarios'];
    }
    public function columnWidths(): array
    {
        return[
            'C'=>40,
            'I'=>80
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            'C'=>[
                'alignment'=>[
                    'wrapText'=>true
                ]
            ]
        ];
    }
    public function map($prestamo): array
    {
        return[
            $prestamo->activo->sucursal->empresa->alias.' - '.$prestamo->activo->sucursal->nombre,
            $prestamo->activo->num_equipo,
            $prestamo->activo->descripcion,
            $prestamo->usuario->nombres .' '.$prestamo->usuario->apellido_p.' '.$prestamo->usuario->apellido_m,
            $prestamo->usuario->puesto->nombre,
            Carbon::create($prestamo->fecha)->locale('es')->isoFormat('D MMM YYYY hh:mm a'),
            $prestamo->fecha_devuelto==null?'Pendiente':Carbon::create($prestamo->fecha_devuelto)->locale('es')->isoFormat('D MMM YYYY hh:mm a'),
            $prestamo->fecha_devuelto==null?'':$this->getDiferencia($prestamo),
            $prestamo->detalles
        ];
    }

    public function getDiferencia($prestamo):string
    {
        $f1=Carbon::create($prestamo->fecha);
        $f2=Carbon::create($prestamo->fecha_devuelto);
        $diferencia=$f1->diff($f2);
        $result='';
        if($diferencia->d>0){
            $result.=$diferencia->d.' día(s)';
        }
        if($diferencia->h>0){
            $result.=' '.$diferencia->h.' hora(s)';
        }
        if($diferencia->i>0){
            $result.=' '.$diferencia->i.' minuto(s)';
        }
        return $result;
    }
    public function query()
    {
        $fechas=[
            Carbon::create($this->fecha_in)->startOfDay()->toDateTimeString(),
            Carbon::create($this->fecha_fin)->endOfDay()->toDateTimeString()
        ];
        return Prestamo::whereBetween('fecha',$fechas)->whereHas('activo',function(Builder $activo){
            $activo->whereIn('sucursal_id',$this->sucursales->pluck('id_sucursal'))->whereHas('tipos',function(Builder $tipo){
                $tipo->whereIn('sucursal_id',$this->sucursales->pluck('id_sucursal'))->where('id_giro', $this->giro);
            });
        });
    }

    public function afterSheet(AfterSheet $event){
        $headers='A1:I1';
        $general='A1:I'.$event->getSheet()->getDelegate()->getHighestRow();
        $event->getDelegate()->getStyle($headers)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],'fill' =>[
                'fillType'=> Fill::FILL_SOLID,
                'color'=> ['argb'=>'063198']
            ]
        ]);
        //aplicamos estilos de manera general a toda la tabla
        $event->getDelegate()->getStyle($general)->applyFromArray([
            'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
            ],'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'ff000000'],
                ]
            ]
        ]);
    }
}
