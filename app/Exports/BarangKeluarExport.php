<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangKeluarExport implements FromCollection, WithHeadings, WithStyles
{
    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($search = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Fetch data from API with date filter
        $response = Http::withOptions(['verify' => false])
            ->get('https://doaibutiri.my.id/gudang/api/laporan/barangkeluar', [
                'search' => $this->search,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to retrieve data from the API.');
        }

        $data = $response->json()['data'] ?? [];
        $exportData = collect();

        foreach ($data as $item) {
            $details = json_decode($item['detail'], true);
            foreach ($details as $detail) {
                $exportData->push([
                    'SerialNumber' => $detail['serial_number'] ?? '-',
                    'ItemName' => $detail['nama_barang'] ?? '-',
                    'ItemType' => $detail['nama_jenis_barang'] ?? '-',
                    'SupplierName' => $detail['nama_supplier'] ?? '-',
                    'CustomerName' => $item['nama_customer'] ?? '-',
                    'Purposes' => $item['nama_keperluan'] ?? '-',
                    'Date' => $item['tanggal'] ?? '-',
                ]);
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Serial Number',
            'Item Name',
            'Item Type',
            'Supplier Name',
            'Customer Name',
            'Purposes',
            'Date'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('ADD8E6'); // Light blue for header

        // Set column width
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set background colors for rows based on customer
        $lastRow = $sheet->getHighestRow();
        $previousCustomer = null;
        $colors = ['FFE6E6', 'E6FFE6', 'E6E6FF']; // Light Red, Light Green, Light Blue
        $colorIndex = 0;

        for ($row = 2; $row <= $lastRow; $row++) {
            $currentCustomer = $sheet->getCell("E{$row}")->getValue(); // CustomerName column

            // Change color if customer changes
            if ($currentCustomer !== $previousCustomer) {
                $fillColor = $colors[$colorIndex % count($colors)]; // Rotate through colors
                $colorIndex++;
                $previousCustomer = $currentCustomer;
            }

            // Apply background color to the row
            $sheet->getStyle("A{$row}:H{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle("A{$row}:H{$row}")->getFill()->getStartColor()->setARGB($fillColor);
        }
    }
}
