<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsByDepartmentExport implements FromCollection, WithHeadings
{
    protected $departments;

    public function __construct($departments)
    {
        $this->departments = $departments;
    }

    public function collection()
    {
        return $this->departments->map(function ($department) {
            return [
                $department->name,
                $department->code,
                $department->location ?? 'N/A',
                $department->manager ?? 'N/A',
                $department->assets_count,
                number_format($department->assets_sum_purchase_cost ?? 0, 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Department',
            'Code',
            'Location',
            'Manager',
            'Assets Count',
            'Total Value',
        ];
    }
}
