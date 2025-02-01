<?php

namespace App\Console\Commands;

use App\Models\Activo;
use App\Models\DeptosJoin;
use App\Models\JoinPuesto;
use App\Models\UsersJoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateActivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-activos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'actualizar los usuarios responsables y quienes crearon los activos según la tabla users_join';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $activos=Activo::where('res_actualizado',0)->get();
            $activosCre=Activo::where('cre_actualizado',0)->get();
            $activosPuestos=Activo::where('ps_actualizado',0)->get();
            $activosDeptos=Activo::where('depto_actualizado',0)->get();
            foreach ($activos as $activo) {
                $empAsignado=UsersJoin::firstwhere('id_r',$activo->responsable_id);
                if (isset($empAsignado)) {
                    $activo->responsable_id=$empAsignado->id_c;
                    $activo->res_actualizado=1;
                    $activo->save();
                }else{
                    $activo->responsable_id=null;
                    $activo->save();
                }
            }
            
            foreach ($activosCre as $activo) {
                $empCreador=UsersJoin::firstwhere('id_r',$activo->created_user);
                if (isset($empCreador)) {
                    $activo->created_user=$empCreador->id_c;
                    $activo->cre_actualizado=1;
                    $activo->save();
                }else{
                    $activo->created_user=null;
                    $activo->save();
                }
            }

            foreach ($activosPuestos as $activo) {
                $psId=JoinPuesto::firstwhere('id_r',$activo->puesto_id);
                if (isset($psId)) {
                    $activo->puesto_id=$psId->id_c;
                    $activo->ps_actualizado=1;
                    $activo->save();
                }else{
                    $activo->puesto_id=null;
                    $activo->save();
                }
            }

            foreach ($activosDeptos as $activo) {
                $deptoId=DeptosJoin::firstwhere('id_r',$activo->depto_id);
                if (isset($deptoId)) {
                    $activo->departamento_id=$deptoId->id_c;
                    $activo->depto_actualizado=1;
                    $activo->save();
                }else{
                    $activo->departamento_id=null;
                    $activo->save();
                }
            }
    
        } catch (\Throwable $th) {
            Log::error('Error al realizar la acción', ['error' => $th->getMessage()]);
        }
    }
}
