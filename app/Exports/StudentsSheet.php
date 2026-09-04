<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Enumerable;

class StudentsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
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
        $query = Student::with('course');
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59']);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Course / Instrument', 'Enrolled Level', 'Age', 'Country', 'Status'];
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
            $student->age,
            $student->country,
            $student->status,
        ];
    }

    public function title(): string
    {
        return 'Students';
    }
}
