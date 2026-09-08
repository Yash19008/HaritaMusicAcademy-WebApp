<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Enumerable;

class TeachersSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Enumerable
    {
        $query = Teacher::query();
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59']);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Music Categories', 'Level', 'Specialization', 'Status'];
    }

    public function map($teacher): array
    {
        return [
            $teacher->id,
            $teacher->name,
            $teacher->email,
            $teacher->phone,
            $teacher->categories ?? 'Unassigned',
            $teacher->level,
            $teacher->specialization,
            $teacher->status,
        ];
    }

    public function title(): string
    {
        return 'Teachers';
    }
}
