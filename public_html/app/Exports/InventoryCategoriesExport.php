<?php

namespace App\Exports;

use App\Models\InventoryCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryCategoriesExport implements FromCollection, WithHeadings
{
    /**
     * Retorna la colección de categorías de inventario para exportar.
     */
    public function collection()
    {
        
        return InventoryCategory::select('id', 'name', 'active')->get()->map(function ($category) {
            $category->active = $category->active ? 'Activo' : 'Inactivo'; 
            return $category;
        });
    }

    /**
     * Define los encabezados del archivo Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre de la Categoría',
            'Estado'
        ];
    }
}
