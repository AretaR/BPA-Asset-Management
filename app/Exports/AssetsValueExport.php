<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsValueExport implements FromCollection, WithHeadings
{
    protected $categoryValues;

    public function __construct($categoryValues)
    {
        $this->categoryValues = $categoryValues;
    }

    public function collection()
    {
        return $this->categoryValues->map(function ($category) {
            return [
                $category->name,
                $category->assets_count,
                number_format($category->assets_sum_purchase_cost ?? 0, 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category',
            'Assets Count',
            'Total Value',
        ];
    }
}
