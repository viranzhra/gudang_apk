<?php

namespace App\Imports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangMasukImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Assuming the Excel file has these columns in the right order
        return new BarangMasuk([
            'barang' => $row[0],
            'jumlah' => $row[1],
            'keterangan' => $row[2],
            'tanggal_masuk' => $row[3],
        ]);
    }
}
