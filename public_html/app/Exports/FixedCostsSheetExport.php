<?php

namespace App\Exports;

use App\Models\FixedCostConfig;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FixedCostsSheetExport implements FromCollection, WithHeadings
{
    protected $orgId;

    public function __construct($orgId)
    {
        $this->orgId = $orgId;
    }

    public function collection()
    {
        return FixedCostConfig::where('org_id', $this->orgId)
            ->select(
                'id',
                'org_id',
                'fixed_charge_penalty',
                'replacement_penalty',
                'late_fee_penalty',
                'expired_penalty',
                'max_covered_m3'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            "ID",
            "ID Organización",
            "Cargo Fijo",
            "Penalización por Reposición",
            "Mora",
            "Penalización por Vencimiento",
            "Máx. m³ Cubiertos"
        ];
    }
}
