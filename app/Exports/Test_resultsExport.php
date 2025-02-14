<?php

namespace App\Exports;

use App\course_test_result;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Test_resultsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(int $course_id)
    {
        $this->course_id = $course_id;
    }

    public function query()
    {
        return course_test_result::query()->where('course_id', $this->course_id);
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->no_test,
            $row->member->name,
            $row->member->email,
            $row->degree.'%',
            $row->locale,
            intval($row->flag),
        ];
    }

    public function headings(): array
    {
        return ['#','No. Test','Name','Email','Degree','Locale','Flag'];
    }
}
