<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(
        Member::join('services', 'members.id', '=', 'services.member_id')
            ->join('readings', 'services.id', '=', 'readings.service_id')
            ->join('cities', 'members.city_id', '=', 'cities.id') 
            ->select('members.id', 'members.rut', 'members.full_name', 
            \DB::raw("CONCAT(members.address, ', ', cities.name_city) AS full_address"),  
            'members.partner'
             
            )
            ->get()
            
        );
    }
    
    public function headings(): array
    {
        return [
            "ID", 
            "Rut Cliente", 
            "Nombre Completo", 
            "Direccion",
            "Tipo",
            "Servicios"
            
        ];
    }
}