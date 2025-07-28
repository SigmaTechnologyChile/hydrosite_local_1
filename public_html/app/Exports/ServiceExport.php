<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ServiceExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(
            Service::join('members', 'services.member_id', '=', 'members.id')
                ->join('locations', 'services.locality_id', '=', 'locations.id')
                ->select(
                    'services.id as service_id', 
                    DB::raw("CONCAT(members.full_name) as member_name"), 
                    'locations.name as sector', 
                    'services.meter_number', 
                    'services.invoice_type', 
                    'services.meter_plan', 
                    'services.percentage', 
                    'services.diameter' 
                )
                ->get()
        );
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            "ID Servicio", 
            "Miembro", 
            "Sector", 
            "N° Medidor", 
            "Boleta/Factura", 
            "MIDEPLAN", 
            "Porcentaje", 
            "Diámetro"
        ];
    }
}