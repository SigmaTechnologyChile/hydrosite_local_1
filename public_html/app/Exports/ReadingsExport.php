<?php

namespace App\Exports;

use App\Models\Reading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReadingsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reading::join('services','readings.service_id','services.id')
        ->join('members','services.member_id','members.id')
        ->select("readings.id", "readings.previous_reading", "readings.current_reading","services.nro","members.rut", "members.full_name")
        ->get();
    }
    
    public function headings(): array
    {
        return [
            "ID", 
            "Lectura Anterior", 
            "Lectura Actual",
            "N° Servicio",
            "Rut Cliente",
            "Nombre Completo"
            ];
    }
}
