<?php

namespace App\Imports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangMasukImport implements ToModel
{
    public function model(array $row)
    {
        return new BarangMasuk([
            'nama_barang' => $row[0],
            'jenis_barang' => $row[1],
            'supplier' => $row[2],
            'tanggal_masuk' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]),
            'keterangan' => $row[4],
            'jumlah' => $row[5],
            // Sesuaikan dengan struktur file Excel yang diupload
        ]);
    }
}

