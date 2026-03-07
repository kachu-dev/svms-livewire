<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Violation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllViolationExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected $violations) {}

    public function collection(): Collection
    {
        return $this->violations ?? Violation::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Count',
            'Classification',
            'Violation',
            'Remark',
            'Status',
            'Date',
        ];
    }

    public function map($violation): array
    {
        return [
            $violation->student_id,
            $violation->student_name,
            $violation->minor_offense_number,
            $violation->classification_snapshot,
            $violation->violation_type_code_snapshot .' - '. $violation->violation_type_name_snapshot,
            $violation->violation_remark_snapshot,
            $violation->status,
            $violation->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold the header row
        ];
    }
}
