<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            [
                'location_id' => 1,
                'nombre' => 'Juan Pérez',
                'telefono' => '912345678',
                'order_by' => 1,
                'numero' => '1001',
            ],
            [
                'location_id' => 1,
                'nombre' => 'Ana Gómez',
                'telefono' => '987654321',
                'order_by' => 2,
                'numero' => '1002',
            ],
            [
                'location_id' => 2,
                'nombre' => 'Carlos Ruiz',
                'telefono' => '912111111',
                'order_by' => 1,
                'numero' => '2001',
            ],
        ]);
    }
}
