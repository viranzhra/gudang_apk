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
        // Ambil data dari API dengan filter tanggal
        $response = Http::withOptions(['verify' => false])
            ->get('https://doaibutiri.my.id/gudang/api/laporan/barangkeluar', [
                'search' => $this->search,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Gagal mengambil data dari API.');
        }

        $data = $response->json()['data'] ?? [];
        $exportData = collect();

        foreach ($data as $item) {
            $details = json_decode($item['detail'], true);
            foreach ($details as $detail) {
                $exportData->push([
                    'SerialNumber' => $detail['serial_number'] ?? 'N/A',
                    'ItemName' => $detail['nama_barang'] ?? 'N/A',
                    'ItemType' => $detail['nama_jenis_barang'] ?? 'N/A',
                    'SupplierName' => $detail['nama_supplier'] ?? 'N/A',
                    'CustomerName' => $item['nama_customer'] ?? 'N/A',
                    'Amount' => $item['jumlah'] ?? 'N/A',
                    'Purposes' => $item['nama_keperluan'] ?? 'N/A',
                    'Date' => $item['tanggal'] ?? 'N/A',
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
            'Amount',
            'Purposes',
            'Date'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // // Format tanggal kop laporan
        // $formattedStartDate = $this->startDate ? date('Y-m-d', strtotime($this->startDate)) : 'awal';
        // $formattedEndDate = $this->endDate ? date('Y-m-d', strtotime($this->endDate)) : 'akhir';
 
        // // Tambahkan kop laporan di baris pertama
        // $sheet->setCellValue('A1', 'Laporan pengeluaran barang tanggal ' . $formattedStartDate . ' hingga ' . $formattedEndDate);
        // $sheet->mergeCells('A1:H1'); // Menggabungkan sel untuk kop laporan
        // $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        // $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Mengatur gaya untuk header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('ADD8E6'); // Warna biru muda (Light Blue)

        // Menambahkan border ke header
        $sheet->getStyle('A1:H1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Menambahkan border untuk seluruh data (A2 hingga H terakhir)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Mengatur lebar kolom
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
