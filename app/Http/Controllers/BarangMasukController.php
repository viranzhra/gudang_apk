<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\SerialNumber;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Imports\BarangMasukImport; // Correct namespace for the import class
use Maatwebsite\Excel\Facades\Excel; // Ensure this is also imported

class BarangMasukController extends Controller
{

    public function downloadTemplate()
    {
        $filePath = public_path('templates/barangmasuk.xlsx');
        return response()->download($filePath);
    }

    public function getData()
    {
        $items = BarangMasuk::select(['id', 'item', 'description', 'serial_number', 'status_item', 'requirement']);
        
        return DataTables::of($items)
            ->addIndexColumn()
            ->toJson(); // Mengubah ke format JSON secara eksplisit
    }

	public function preview(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    $file = $request->file('file');
    $data = Excel::toArray(new BarangMasukImport, $file);
    $data = $data[0]; // Ambil sheet pertama

    return view('barangmasuk.index', compact('data', 'file'));
}

	public function uploadExcel(Request $request)
	{
		// Validasi file yang diunggah
		$request->validate([
			'file' => 'required|file|mimes:xlsx,xls,csv',
		]);

		// Baca data dari file Excel
		$data = Excel::toArray(new BarangMasukImport, $request->file('file'));

		// Cek apakah data ada
		if (empty($data) || empty($data[0])) {
			return redirect()->back()->withErrors('File Excel tidak memiliki data yang valid.');
		}

		// Mengumpulkan semua serial number dari data Excel
		$serialNumbers = [];
		foreach ($data[0] as $row) {
			if (!empty($row['serial_number'])) {
				$serialNumbers[] = $row['serial_number'];
			}
		}

		// Jika tidak ada serial number yang valid, hentikan proses
		if (empty($serialNumbers)) {
			return redirect()->back()->withErrors('Tidak ada serial number yang valid di file Excel.');
		}

		// Cek serial number yang sudah ada di database
		$existingSerialNumbers = SerialNumber::whereIn('serial_number', $serialNumbers)->pluck('serial_number')->toArray();

		// Jika ditemukan serial number yang sudah digunakan
		if (!empty($existingSerialNumbers)) {
			return redirect()->back()->withErrors('Serial number sudah terpakai: ' . implode(', ', $existingSerialNumbers));
		}

		// Inisialisasi variabel untuk menyimpan pesan
		$successCount = 0;
		$errorCount = 0;
		$notificationMessages = [];

		// Proses data dari Excel
		foreach ($data[0] as $row) {
			// Validasi baris data sebelum diproses
			if (empty($row['barang_id']) || empty($row['serial_number']) || empty($row['kondisi_barang'])) {
				$errorCount++;
				continue; // Jika data tidak lengkap, lewati ke baris berikutnya
			}

			// Ambil data dari setiap baris
			$nama_barang = $row['barang_id'];
			$keterangan = $row['keterangan'] ?? null;
			// $jumlah = $row['jumlah'];
			$serial_number = $row['serial_number'];
			$kondisi_barang = $row['kondisi_barang'];
			$kelengkapan = $row['kelengkapan'] ?? null;

			// Buat array data untuk request ke API
			$apiData = [
				'barang_id' => $nama_barang,
				'keterangan' => $keterangan,
				'tanggal' => now()->format('Y-m-d'),
				'serial_numbers' => ["$serial_number"],
				'status_barangs' => [$kondisi_barang],
				'kelengkapans' => [$kelengkapan],
			];

			// Kirim data ke API menggunakan HTTP POST
			$response = Http::post('https://doaibutiri.my.id/gudang/api/barangmasuk/excel', $apiData);

			// Cek apakah respons API sukses
			if ($response->successful()) {
				$successCount++;
				// Tampilkan pesan sukses umum
				$notificationMessages[] = "Success: Data berhasil diimpor."; // Tampilkan "Success"
			} else {
				$errorCount++;
				$responseData = $response->json();
				// Menyusun pesan kesalahan berdasarkan respons API
				$errorMessage = $responseData['message'] ?? 'Terjadi kesalahan saat mengimpor data.';
				// Tampilkan pesan kesalahan
				$notificationMessages[] = "Error: {$errorMessage}"; // Tampilkan error
			}
		}
		
		// Menampilkan notifikasi berdasarkan jumlah keberhasilan dan kesalahan
		if ($successCount > 0) {
			session()->flash('success', "$successCount data berhasil diimpor.");
		}

		if ($errorCount > 0) {
			session()->flash('notifications', $notificationMessages);
		}

		// Jika ada kesalahan, tampilkan semua pesan kesalahan
		if ($errorCount > 0) {
			$finalMessage = "Data gagal diimpor, silakan periksa kembali data Anda.";
			$notificationMessages[] = $finalMessage;
			session()->flash('notifications', $notificationMessages);
		} else {
			$finalMessage = "$successCount data berhasil diimpor.";
			session()->flash('success', $finalMessage);
		}

		return redirect()->back();
	}

	public function index(Request $request)
	{
        return view('barangmasuk.index');
	}

	public function create($id = null)
	{
		$response = Http::withToken(session('token'))->get(config('app.api_url') . '/barangmasuk/create');

		if ($response->successful()) {
			$data = $response->json();
			$jenis_barang = collect($data['jenis_barang'])->map(function ($item) {
				return (object) $item;
			});
			$barang = collect($data['barang'])->map(function ($item) {
				return (object) $item;
			});
			$supplier = collect($data['supplier'])->map(function ($item) {
				return (object) $item;
			});
			$status_barang = collect($data['status_barang'])->map(function ($item) {
				return (object) $item;
			});

			$barangMasuk = null;
			$jenis_barang_id = null;
			$barangbyjenis = null;

			if ($id !== null) {
				$barangMasukResponse = Http::withToken(session('token'))->get(config('app.api_url') . '/barangmasuk/create/' . $id);
				if ($barangMasukResponse->successful()) {
					$barangMasukData = $barangMasukResponse->json();
					$barangMasuk = isset($barangMasukData['barangMasuk']) ? (object) $barangMasukData['barangMasuk'] : null;
					$jenis_barang_id = $barangMasukData['jenis_barang_id'] ?? null;
					$barangbyjenis = isset($barangMasukData['barangbyjenis']) ? collect($barangMasukData['barangbyjenis'])->map(function ($item) {
						return (object) $item;
					}) : collect();
				}
			}

			return view('barangmasuk.create', compact('barangMasuk', 'supplier', 'barang', 'barangbyjenis', 'jenis_barang', 'jenis_barang_id', 'status_barang'));
		}

		return redirect('/barangmasuk')->withErrors('Gagal mengambil data untuk membuat barang masuk baru.');
	}
	
	public function getBarangByJenis($id)
	{
		$response = Http::withToken(session('token'))->get(config('app.api_url') . '/barangmasuk/get-by-jenis/' . $id);
		
		if ($response->successful()) {
			$barang = $response->json();
			return response()->json($barang);
		}
		
		return response()->json(['error' => 'Failed to fetch data'], 500);
	}

	/*public function createSelected($id)
	{
		$barangMasuk = BarangMasuk::findOrFail($id);

		$supplier = DB::table('supplier')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$barang = DB::table('barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$jenis_barang = DB::table('jenis_barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$status_barang = DB::table('status_barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();

		$bm_kode = DB::table('barang_masuk')->orderBy('id', 'desc')->value('bm_kode');

		if ($bm_kode) {
			$angkaTerakhir = intval(substr($bm_kode, 3));
			$angkaSelanjutnya = $angkaTerakhir + 1;
			$bm_kode_value = 'BM_' . str_pad($angkaSelanjutnya, 3, '0', STR_PAD_LEFT);
		} else {
			$bm_kode_value = 'BM_' . str_pad(1, 3, '0', STR_PAD_LEFT);
		}

		return view('barangmasuk.create', compact('barangMasuk', 'supplier', 'barang', 'jenis_barang', 'status_barang', 'bm_kode_value'));
	}*/

	public function store(Request $request): RedirectResponse
	{
		$response = Http::withToken(session('token'))->post(config('app.api_url') . '/barangmasuk', $request->all());

		if ($response->successful()) {
			return redirect('/barangmasuk')->with('success', 'Data berhasil ditambahkan!');
		}

		if ($response->status() === 422) {
			$responseData = $response->json();
			if (isset($responseData['message']) && strpos($responseData['message'], 'Serial number sudah terpakai') !== false) {
				return back()->withErrors($responseData['message'])->withInput();
			}
		}

		return back()->withErrors('Gagal menambahkan data barang.')->withInput();
	}

	public function delete($id)
	{
		$response = Http::withToken(session('token'))->delete(config('app.api_url') . '/barangmasuk/' . $id);

		if ($response->successful()) {
			return redirect('/barangmasuk')->with('success', 'Data berhasil dihapus!');
		}

			return back()->withErrors('Gagal menghapus data barang masuk.');
	}

	public function deleteSelected(Request $request)
	{
		$response = Http::withToken(session('token'))->post(config('app.api_url') . '/barangmasuk/delete-selected', [
			'ids' => $request->input('ids')
		]);

		if ($response->successful()) {
			return redirect('/barangmasuk')->with('success', 'Data terpilih berhasil dihapus!');
		}

		return back()->withErrors('Gagal menghapus data barang masuk terpilih.');
	}

}