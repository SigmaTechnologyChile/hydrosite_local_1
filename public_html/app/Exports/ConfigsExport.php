<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ConfigsExport implements WithMultipleSheets
{
    protected $orgId;

    public function __construct($orgId)
    {
        $this->orgId = $orgId;
    }

    public function sheets(): array
    {
        return [
            new FixedCostsSheetExport($this->orgId),
            new TierConfigSheetExport($this->orgId),
        ];
    }
}
