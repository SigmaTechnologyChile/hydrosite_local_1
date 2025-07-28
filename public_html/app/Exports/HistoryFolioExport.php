<?php

namespace App\Exports;

use App\Models\Folio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoryFolioExport implements FromCollection, WithHeadings
{
    // Este método obtiene los datos de la base de datos para exportar.
    public function collection()
    {
        // Obtén todos los folios con los campos seleccionados.
        return Folio::select('id', 'message', 'codigoSii', 'fechaIngreso', 'fechaCaf', 'desde', 'hasta', 'fechaVencimiento', 'tipoDte', 'foliosDisponibles', 'ambiente', 'errors')
                    ->get();
    }

    // Este método define los encabezados del archivo Excel.
    public function headings(): array
    {
        return [
            'ID', 'Mensaje', 'Código SII', 'Fecha Ingreso', 'Fecha CAF', 'Desde', 'Hasta', 'Fecha Vencimiento', 'Tipo DTE', 'Folios Disponibles', 'Ambiente', 'Errores'
        ];
    }
}