<?php

namespace App\Console\Commands;

use App\Models\Departamentos;
use App\Models\DeptosJoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MergeDeptos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:merge-deptos';

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
            $psConcentradora=Departamentos::all();
            $psRrsguardo=DB::connection('resguardo')->table('departamento')->get();
            foreach($psConcentradora as $psC){
                foreach($psRrsguardo as $psR){
                    if(strcasecmp($psC->nombre,$psR->nombre)==0){
                        $usMerge=new DeptosJoin();
                        $usMerge->name_c=$psC->nombre;
                        $usMerge->name_r=$psR->nombre;
                        $usMerge->id_c=$psC->id_departamento;
                        $usMerge->id_r=$psR->id_departamento;
                        $usMerge->save();
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Error al realizar la acción', ['error' => $th->getMessage()]);
        }
    }
}
