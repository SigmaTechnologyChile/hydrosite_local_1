<?php

namespace App\Exports;

use App\Models\TierConfig;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TierConfigSheetExport implements FromCollection, WithHeadings
{
    protected $orgId;

    public function __construct($orgId)
    {
        $this->orgId = $orgId;
    }

    public function collection()
    {
        return DB::table('tier_config')
            ->join('fixed_costs_config', 'tier_config.org_id', '=', 'fixed_costs_config.id')
            ->where('fixed_costs_config.org_id', $this->orgId)
            ->select(
                'tier_config.id',
                'tier_config.org_id',
                'tier_config.tier_name',
                'tier_config.range_from',
                'tier_config.range_to',
                'tier_config.value'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            "ID",
            "ID Configuración Costos Fijos",
            "N° Tramo",
            "Desde (m³)",
            "Hasta (m³)",
            "Valor ($)"
        ];
    }
}
