<?php

namespace App\Exports;

use App\member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class membersExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
      {
          return member::take(100)->get();
      }

      public function map($row): array
      {
          return [
              $row->id,
              $row->name,
              $row->email,
              $row->phone,
              $row->courses->count(),
              $row->test_results->unique('course_id')->count(),

          ];
      }

      public function headings(): array
      {
          return ['#','Name','Email','Phone','count courses','count test'];
      }
  }
