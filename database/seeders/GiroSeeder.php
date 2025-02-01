<?php

namespace Database\Seeders;

use App\Models\Giro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Giro::create(['nombre'=>'transporte','codigo'=>'TR-',]);
        Giro::create(['nombre'=>'Computo','codigo'=>'EQC-',]);
        Giro::create(['nombre'=>'mobiliario y equipo de oficina','codigo'=>'MO-',]);
        Giro::create(['nombre'=>'Maquinaria y Equipo','codigo'=>'MA-',]);
        Giro::create(['nombre'=>'Herramientas','codigo'=>'HE-',]);
    }
}
