<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            'nombre' => 'user_default',
            'valor' => 302,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'tipo_eqt_default',
            'valor' => 420,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'area_default',
            'valor' => 51,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'puesto_default',
            'valor' => 195,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'empleado_default',
            'valor' => 302,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'depreciacion_default',
            'valor' => 1,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'tipo_eqc_default',
            'valor' => 421,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'tipo_moe_default',
            'valor' => 422,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'tipo_mae_default',
            'valor' => 423,
        ]);
        DB::table('configuraciones')->insert([
            'nombre' => 'tipo_her_default',
            'valor' => 424,
        ]);
    }
}
