<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('categorias')->truncate();
        $now = Carbon::now();
        $ingresos = [
            'Venta de Agua (Total Consumo)',
            'Cuotas de Incorporación (Cuotas de Incorporación)',
            'Venta de Medidores (Otros Ingresos)',
            'Trabajos en Domicilio (Otros Ingresos)',
            'Subsidios (Otros Ingresos)',
            'Otros Aportes (Otros Ingresos)',
            'Multas Inasistencia (Otros Ingresos)',
            'Otras Multas (Otros Ingresos)',
        ];
        $egresos = [
            'Energía Eléctrica (Gastos de Operación)',
            'Sueldos y Leyes Sociales (Gastos de Operación)',
            'Otras Ctas. (Agua, Int. Cel.) (Gastos de Operación)',
            'Mantención y reparaciones Instalaciones (Gastos de Mantención)',
            'Insumos y Materiales (Oficina) (Gastos de Administración)',
            'Materiales e Insumos (Red) (Gastos de Mejoramiento)',
            'Viáticos / Seguros / Movilización (Otros Gastos)',
            'Gastos por Trabajos en domicilio (Gastos de Mantención)',
            'Mejoramiento / Inversiones (Gastos de Mejoramiento)',
        ];
        foreach ($ingresos as $nombre) {
            DB::table('categorias')->insert([
                'nombre' => $nombre,
                'tipo' => 'ingreso',
                'grupo' => null,
            ]);
        }
        foreach ($egresos as $nombre) {
            DB::table('categorias')->insert([
                'nombre' => $nombre,
                'tipo' => 'egreso',
                'grupo' => null,
            ]);
        }
    }
}
