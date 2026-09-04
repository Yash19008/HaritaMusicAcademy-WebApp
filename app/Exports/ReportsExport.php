<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Export;

class ReportsExport implements WithMultipleSheets, Export
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new AdmissionsSheet($this->startDate, $this->endDate),
            new TeachersSheet($this->startDate, $this->endDate),
            new StudentsSheet($this->startDate, $this->endDate),
        ];
    }
}
