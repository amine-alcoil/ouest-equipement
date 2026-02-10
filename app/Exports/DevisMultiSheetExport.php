<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DevisMultiSheetExport implements WithMultipleSheets
{
    protected array $standard;
    protected array $specific;

    public function __construct(array $standard, array $specific)
    {
        $this->standard = $standard;
        $this->specific = $specific;
    }

    public function sheets(): array
    {
        return [
            new DevisExport($this->standard, 'standard'),
            new DevisExport($this->specific, 'specific'),
        ];
    }
}