<?php

namespace App\Console\Commands;

use App\Models\JoinPuesto;
use App\Models\Puesto;
use App\Models\Puestos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MergePuestos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:merge-puestos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $psConcentradora=Puesto::all();
            $psRrsguardo=DB::connection('resguardo')->table('puesto')->get();
            foreach($psConcentradora as $psC){
                foreach($psRrsguardo as $psR){
                    if(strcasecmp($psC->nombre,$psR->nombre)==0){
                        $usMerge=new JoinPuesto();
                        $usMerge->name_c=$psC->nombre;
                        $usMerge->name_r=$psR->nombre;
                        $usMerge->id_c=$psC->id_puesto;
                        $usMerge->id_r=$psR->id_puesto;
                        $usMerge->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error al realizar la acción', ['error' => $th->getMessage()]);
        }
    }
}
