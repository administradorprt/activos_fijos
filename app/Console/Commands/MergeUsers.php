<?php

namespace App\Console\Commands;

use App\Models\Empleado;
use App\Models\User;
use App\Models\UsersJoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MergeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:merge-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buscar usuarios que coinciden entre tb resguardo con concentradora';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $usConcentradora=Empleado::all();
            $usRrsguardo=DB::connection('resguardo')->table('empleado')->get();
            foreach($usConcentradora as $usC){
                foreach($usRrsguardo as $usR){
                    if(strcasecmp($usC->nombres,$usR->nombre)==0 && strcasecmp($usC->apellido_p,$usR->apellido_paterno)==0 && strcasecmp($usC->apellido_m,$usR->apellido_materno)==0){
                        $usMerge=new UsersJoin();
                        $usMerge->name_c=$usC->nombres.' '.$usC->apellido_p.' '.$usC->apellido_m;
                        $usMerge->name_r=$usR->nombre.' '.$usR->apellido_paterno.' '.$usR->apellido_materno;
                        $usMerge->id_c=$usC->id_empleado;
                        $usMerge->id_r=$usR->id_empleado;
                        $usMerge->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error al realizar la acción', ['error' => $th->getMessage()]);
        }
    }
}
