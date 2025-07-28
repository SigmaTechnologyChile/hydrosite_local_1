<?php

namespace App\Exports;

use App\Models\Member; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PaymentsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
       return DB::table('orders')
            ->join('members', 'orders.dni', '=', 'members.rut')
            ->select(
                'members.id as client_id',
                'members.rut',
                'members.first_name',
                'members.last_name',
                'orders.qty',
                'payment_detail',
                'orders.total'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            "N° Cliente",
            "RUT/RUN",
            "Nombres",
            "Apellidos",
            "Servicios",
            "Estado",
            "TOTAL ($)"
        ];
    }
}