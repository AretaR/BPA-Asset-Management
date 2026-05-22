<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsByStatusExport implements FromCollection, WithHeadings
{
    protected $statusData;

    public function __construct($statusData)
    {
        $this->statusData = $statusData;
    }

    public function collection()
    {
        $data = collect($statusData)->map(function ($data, $status) {
            $totalCount = array_sum(array_column($this->statusData, 'count'));
            $percentage = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;

            return [
                ucfirst($status),
                $data['count'],
                number_format($data['total_value'], 2),
                $percentage . '%',
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Status',
            'Count',
            'Total Value',
            'Percentage',
        ];
    }
}
