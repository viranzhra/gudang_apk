<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Http;

class BASTController extends Controller
{
    public function index($id)
    {
        // Mendapatkan data permintaan barang keluar
        if (!app('permission')->can('item request.viewAll')) {
            $response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . "/permintaanbarangkeluar/onlyfor");
        } else {
            $response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . "/permintaanbarangkeluar");
        }
        
        $permintaan_barang_keluar = $response->json();
        $allData = $permintaan_barang_keluar['data'];
        $filteredData = collect($allData)->firstWhere('id', $id);
        
        // Mendapatkan detail barang dari permintaan barang keluar
        $barang_details = Http::withToken(session('jwt_token'))->get(config('app.api_url') . "/permintaanbarangkeluar/{$id}")->json();

        // Mendapatkan serial number detail dari permintaan barang keluar
        $serial_numbers = Http::withToken(session('jwt_token'))->get(config('app.api_url') . "/permintaanbarangkeluar/show-detail-sn/{$id}")->json();

        // Membuat TemplateProcessor untuk file DOCX
        $templateProcessor = new TemplateProcessor(public_path('templates/bast.docx'));

        // Mengisi data umum ke template
        // $tgl_bln = date('dm', strtotime($filteredData['updated_at']));
        $tgl_bln = date('dm');
        $templateProcessor->setValue('${tgl_bln}', $tgl_bln);     

        // $bln_rmw = date('m', strtotime($filteredData['updated_at']));
        $bln_rmw = date('m');
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bln_rmw = $romawi[intval($bln_rmw) - 1];
        $templateProcessor->setValue('${bln_rmw}', $bln_rmw);     

        // $tahun = date('Y', strtotime($filteredData['updated_at']));
        $tahun = date('Y');
        $templateProcessor->setValue('${tahun}', $tahun);

        // $tanggal_keluar = date('d F Y', strtotime($filteredData['updated_at']));
        $tanggal_keluar = date('d F Y');
        $templateProcessor->setValue('${tanggal_keluar}', $tanggal_keluar);

        $project = $filteredData['ba_project'] ?? null;
        $templateProcessor->setValue('${project}', $project);

        $no_po = $filteredData['ba_no_po'] ?? null;
        $templateProcessor->setValue('${no_po}', $no_po);

        // Membuat data untuk tabel yang akan di-clone
        $tableRows = [];
        $no = 1;
        foreach ($barang_details as $barang) {
            // Mengambil serial number untuk barang tertentu
            $serialNums = collect($serial_numbers)->where('barang_id', $barang['barang_id'])->pluck('serial_number')->all();
            $serialNumbersString = implode(', ', $serialNums);

            // Mengisi data untuk satu baris
            $tableRows[] = [
                'no' => $no,
                'nama_barang' => $barang['nama_barang'],
                'jumlah_barang' => $barang['total_barang'],
                'serial_number' => $serialNumbersString,
            ];
            $no++;
        }

        // Menggandakan baris dalam tabel di Word sesuai dengan jumlah data
        $templateProcessor->cloneRowAndSetValues('no', $tableRows);

        // Menyimpan dokumen Word yang dihasilkan
        $fileName = "BAST - {$filteredData['nama_customer']} - {$tanggal_keluar}.docx";
        $tempFilePath = storage_path("app/public/{$fileName}");
        $templateProcessor->saveAs($tempFilePath);

        // Mengembalikan response untuk mendownload file
        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }
}
