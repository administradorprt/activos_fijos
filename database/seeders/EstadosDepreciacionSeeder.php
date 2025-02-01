<?php

namespace Database\Seeders;

use App\Models\EstadosDepreciacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosDepreciacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadosDepreciacion::create(['nombre'=>'VIGENTE','estado'=>'1']);
        EstadosDepreciacion::create(['nombre'=>'BAJA','estado'=>'1']);
        EstadosDepreciacion::create(['nombre'=>'DEPRECIADO EN USO','estado'=>'1']);
    }
}
