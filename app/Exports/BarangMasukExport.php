<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class BarangMasukExport implements FromCollection, WithHeadings, WithStyles
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
        // Ambil data dari API
        $response = Http::withOptions(['verify' => false])
            ->get('https://doaibutiri.my.id/gudang/api/laporan/barangmasuk', [
                'search' => $this->search,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);

        // Cek apakah respons sukses
        if (!$response->successful()) {
            throw new \Exception('Gagal mengambil data dari API.');
        }

        // Ambil data dari respons API
        $data = $response->json()['data'] ?? [];

        // Ubah data menjadi collection yang diperlukan oleh Excel
        $exportData = collect();

        foreach ($data as $item) {
            $exportData->push([
                'ItemName'      => $item['nama_barang'] ?? 'N/A',
                'ItemType'      => $item['nama_jenis_barang'] ?? 'N/A',
                'SupplierName'  => $item['nama_supplier'] ?? 'N/A',
                'Description'   => $item['keterangan'] ?? 'N/A',
                'Amount'        => $item['jumlah'] ?? 'N/A',
                'Date'          => $item['tanggal'] ?? 'N/A',
            ]);
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Item Type',
            'Supplier Name',
            'Description',
            'Amount',
            'Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur gaya untuk header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('ADD8E6'); // Warna biru muda (Light Blue)

        // Menambahkan border ke header
        $sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Menambahkan border untuk seluruh data (A2 hingga F terakhir)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Mengatur lebar kolom
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
} 