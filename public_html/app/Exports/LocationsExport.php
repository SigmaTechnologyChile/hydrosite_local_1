<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationsExport implements FromCollection, WithHeadings
{
    /**
     * Retorna la colección de sectores para exportar.
     */
    public function collection()
    {
        return Location::select('id', 'name' )->get();
    }

    /**
     * Define los encabezados del archivo Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Sector'
            
        ];
    }
}
