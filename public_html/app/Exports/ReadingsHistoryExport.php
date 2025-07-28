<?php

namespace App\Exports;

use App\Models\Reading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ReadingsHistoryExport implements FromCollection, WithHeadings
{
    protected $serviceId;

    public function __construct($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function collection()
    {
        return Reading::with(['service.member', 'service.location'])
            ->whereHas('service', function($query) {
                $query->where('id', $this->serviceId);
            })
            ->orderBy('period', 'desc')
            ->get()
            ->map(function ($reading) {
                return [
                    'lec' => $reading->id,
                    'period' => $reading->period->format('Y-m'),
                    'location_name' => $reading->service->sector ?? 'N/A',
                    'nro_servicio' => str_pad($reading->service->nro ?? '', 5, '0', STR_PAD_LEFT),
                    'rut' => $reading->service->member->rut ?? 'N/A',
                    'cliente' => $reading->service->member->full_name ?? 'N/A',
                    'period_copy' => $reading->period->format('Y-m-d'),
                    'lecturas' => $reading->previous_reading.' / '.$reading->current_reading,
                    'consumo_m3' => $reading->current_reading - $reading->previous_reading,
                    'valor_consumo_agua' => $reading->vc_water
                ];
            });
    }

    public function headings(): array
    {
        return [
            "Lec.", 
            "Periodo", 
            "Sector", 
            "N° Servicio",
            "RUT",
            "Cliente", 
            "Fecha", 
            "Lecturas (Ant / Act)",
            "Consumo (M³)", 
            "Valor Consumo Agua"
        ];
    }
}