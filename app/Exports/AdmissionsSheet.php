<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Enumerable;

class AdmissionsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
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
        $query = Student::with('course')->latest();
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('joining_date', [$this->startDate, $this->endDate]);
        } else {
            // Default to this month
            $query->whereMonth('joining_date', now()->month)
                  ->whereYear('joining_date', now()->year);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Course / Instrument', 'Enrolled Level', 'Joining Date'];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->email,
            $student->phone,
            $student->course ? $student->course->name : 'Unassigned',
            $student->enrolled_level,
            $student->joining_date,
        ];
    }

    public function title(): string
    {
        return 'Admissions';
    }
}
