<?php

namespace App\Exports;

use App\Models\Inventary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class InventaryExport implements FromCollection, WithHeadings
{
    /**
     * Retorna la colección de inventarios para exportar.
     */
    public function collection()
    {
        return Inventary::select(
            'inventaries.id',  // Especificamos la tabla 'inventaries'
            'inventaries.qxt',
            'inventaries.order_date',
            'inventaries.description',
            'inventaries.amount',
            'inventaries.status',
            'inventaries.location',
            'inventaries.responsible',
            'inventaries.low_date',
            'inventaries.observations',
            'inventories_categories.name as category_name' 
        )
        ->join('inventories_categories', 'inventaries.category_id', '=', 'inventories_categories.id')
        ->get();
    }

    /**
     * Define los encabezados del archivo Excel.
     */
    public function headings(): array
    {
        return [
            "N° Registro",
            "Cantidad",
            "Fecha Último Pedido",
            "Descripción del Artículo",
            "Valor",
            "Estado",
            "Ubicación",
            "Nombre del responsable",
            "Fecha de Traslado o Baja",
            "Observaciones",
            "Categoria"
        ];
    }
    
    
}
