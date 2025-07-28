<?php

namespace App\Exports;

use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SectionsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(
            Section::join('orgs', 'sections.org_id', '=', 'orgs.id') 
                ->select('sections.id', 'sections.name', 'sections.from_to', 'sections.until_to', 'sections.cost')
                ->get()
        );
    }

    public function headings(): array
    {
        return [
            "ID",
            "Nombre del Tramo",
            "Desde",
            "Hasta",
            "Valor ($)"
        ];
    }
}