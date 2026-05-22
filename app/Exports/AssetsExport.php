<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Asset::with(['category', 'department', 'assignedUser'])
            ->get()
            ->map(function ($asset) {
                return [
                    $asset->asset_tag,
                    $asset->name,
                    $asset->category->name ?? 'N/A',
                    $asset->department->name ?? 'N/A',
                    $asset->assignedUser->name ?? 'Unassigned',
                    $asset->serial_number ?? 'N/A',
                    $asset->status,
                    $asset->location ?? 'N/A',
                    $asset->purchase_date?->format('Y-m-d') ?? 'N/A',
                    $asset->purchase_cost,
                    $asset->warranty_expiry?->format('Y-m-d') ?? 'N/A',
                    $asset->manufacturer ?? 'N/A',
                    $asset->model ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Asset Tag',
            'Name',
            'Category',
            'Department',
            'Assigned To',
            'Serial Number',
            'Status',
            'Location',
            'Purchase Date',
            'Purchase Cost',
            'Warranty Expiry',
            'Manufacturer',
            'Model',
        ];
    }
}
