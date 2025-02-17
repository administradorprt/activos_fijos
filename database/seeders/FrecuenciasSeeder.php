<?php

namespace Database\Seeders;

use App\Models\FrecuenciaMantenimiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrecuenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FrecuenciaMantenimiento::create(['name'=>'Semestral','dias'=>180,'manual'=>0]);
        FrecuenciaMantenimiento::create(['name'=>'Anual','dias'=>365,'manual'=>0]);
        FrecuenciaMantenimiento::create(['name'=>'Anual sin definir','dias'=>0,'manual'=>1]);
    }
}
