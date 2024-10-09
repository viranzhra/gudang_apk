<?php

namespace App\Imports;

use App\Models\BarangMasuk;
use App\Models\SerialNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BarangMasukImport implements ToModel, WithHeadingRow, WithValidation
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validate input based on the expected structure in your API
        // Note: Make sure the indexes match the data in your Excel file
        $barangMasuk = BarangMasuk::create([
            'barang_id' => $row['barang_id'],
            'jumlah' => 1, // You may need to adjust this based on your logic
            'keterangan' => $row['keterangan'] ?? null,
            'tanggal' => \Carbon\Carbon::parse($row['tanggal']),
        ]);

        // Handle serial numbers and their details
        $serialNumbers = explode(',', $row['serial_numbers']); // Assuming serial_numbers are comma-separated
        $statusBarangs = explode(',', $row['status_barangs']); // Assuming status_barangs are comma-separated
        $kelengkapans = explode(',', $row['kelengkapans'] ?? ''); // Assuming kelengkapans are optional and comma-separated

        foreach ($serialNumbers as $index => $serialNumber) {
            // Create the serial number
            $serial = SerialNumber::create([
                'serial_number' => trim($serialNumber),
                'barangmasuk_id' => $barangMasuk->id,
            ]);

            // Create details for each serial number
            DetailBarangMasuk::create([
                'barangmasuk_id' => $barangMasuk->id,
                'serial_number_id' => $serial->id,
                'status_barang_id' => $statusBarangs[$index] ?? null, // Ensure this exists
                'kelengkapan' => $kelengkapans[$index] ?? null,
            ]);
        }

        return $barangMasuk;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'barang_id' => 'required|numeric|exists:barangs,id',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
            'serial_numbers' => 'required|array',
            'serial_numbers.*' => 'required|string|max:255',
            'status_barangs' => 'required|array',
            'status_barangs.*' => 'required|exists:status_barang,id',
            'kelengkapans' => 'nullable|array',
            'kelengkapans.*' => 'nullable|string|max:255',
        ];
    }
}
