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

	public function uploadExcel(Request $request) {
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
    
        // Inisialisasi variabel untuk menyimpan pesan
        $errorMessages = [];
        $duplicateSerialNumbers = []; // Array untuk menyimpan serial number yang sudah terpakai
        $failedItems = []; // Array untuk menyimpan nama barang yang gagal diimpor
        $successfulImports = 0; // Counter untuk menyimpan jumlah barang yang berhasil diimpor
    
        // Proses data dari Excel
        foreach ($data[0] as $row) {
            // Validasi baris data sebelum diproses
            if (empty($row['barang_id']) || empty($row['serial_number'])) {
                $errorMessages[] = "Data tidak lengkap untuk barang: {$row['barang_id']}";
                continue; // Jika data tidak lengkap, lewati ke baris berikutnya
            }
    
            // Ambil data dari setiap baris
            $nama_barang = $row['barang_id'];
            $keterangan = $row['keterangan'] ?? null;
            $serial_numbers = explode(',', $row['serial_number']); // Pisahkan SN berdasarkan koma
            $kondisi_barangs = !empty($row['kondisi_barang']) ? explode(',', $row['kondisi_barang']) : []; // Pisahkan kondisi barang jika ada
            $kelengkapan = $row['kelengkapan'] ?? null;
    
            // Periksa apakah jumlah kondisi barang lebih sedikit dari serial number
            if (count($kondisi_barangs) < count($serial_numbers)) {
                // Jika kurang, isi sisa kondisi dengan kondisi yang pertama jika ada
                if (!empty($kondisi_barangs)) {
                    $firstCondition = $kondisi_barangs[0];
                    $kondisi_barangs = array_pad($kondisi_barangs, count($serial_numbers), $firstCondition);
                } else {
                    // Jika tidak ada kondisi sama sekali, isi dengan null
                    $kondisi_barangs = array_fill(0, count($serial_numbers), null);
                }
            }
    
            // Pisahkan kelengkapan berdasarkan tanda "-"
            $kelengkapans = !empty($kelengkapan) ? explode('-', $kelengkapan) : [];
    
            // Pastikan jumlah kelengkapan sesuai dengan jumlah serial number
            if (count($kelengkapans) < count($serial_numbers)) {
                $kelengkapans = array_pad($kelengkapans, count($serial_numbers), null); // Isi yang kosong dengan nilai null
            }
    
            // Jika user memasukkan tanda "_", gantikan dengan null (untuk mengosongkan kelengkapan tersebut)
            foreach ($kelengkapans as &$kel) {
                if (trim($kel) === '_') {
                    $kel = null; // Kosongkan jika ada tanda "_"
                }
            }
    
            // Buat array data untuk request ke API
            $apiData = [
                'barang_id' => $nama_barang,
                'keterangan' => $keterangan,
                'tanggal' => now()->format('Y-m-d'),
                'serial_numbers' => $serial_numbers, // Kirim array SN
                'status_barangs' => $kondisi_barangs, // Kirim kondisi barang yang sesuai
                'kelengkapans' => $kelengkapans,     // Kirim kelengkapan untuk setiap SN
            ];
    
            // Kirim data ke API menggunakan HTTP POST
            $response = Http::post('https://doaibutiri.my.id/gudang/api/barangmasuk/excel', $apiData);
    
            // Cek apakah respons API sukses
            if ($response->successful()) {
                $successfulImports++; // Increment counter for successful imports
                continue; 
            } else {
                // Menyusun pesan kesalahan berdasarkan respons API
                $responseData = $response->json();
                $errorMessage = $responseData['message'] ?? 'Terjadi kesalahan saat mengimpor data.';
    
                // Jika kesalahan adalah serial number sudah terpakai, tambahkan ke array
                if (str_contains($errorMessage, 'Serial number sudah terpakai')) {
                    // Ekstrak serial number yang sudah terpakai
                    preg_match('/Serial number sudah terpakai: (\d+)/', $errorMessage, $matches);
                    if (isset($matches[1])) {
                        $duplicateSerialNumbers[] = $matches[1]; // Tambahkan SN yang sudah terpakai
                    }
                } else {
                    // Tampilkan pesan kesalahan lain selain serial number
                    $failedItems[] = $nama_barang; // Tambahkan barang yang gagal diimpor ke dalam array
                }
            }
        }
    
        // Menampilkan notifikasi berdasarkan kesalahan
        $finalMessage = '';
    
        // Jika ada data yang berhasil diimpor
        if ($successfulImports > 0) {
            $finalMessage .= "$successfulImports data berhasil diimpor."; // Pesan sukses
    
            // Jika ada serial number yang sudah terpakai
            if (!empty($duplicateSerialNumbers)) {
                $serialList = implode(', ', $duplicateSerialNumbers);
                $finalMessage .= " Namun, terdapat " . count($duplicateSerialNumbers) . " data dengan serial number sudah terpakai: $serialList.";
            }
    
            // Jika ada barang yang gagal diimpor
            if (!empty($failedItems)) {
                $failedItemList = implode(', ', $failedItems);
                $finalMessage .= " Terjadi kesalahan saat mengimpor data untuk barang: $failedItemList. Bisa jadi karena data tidak sesuai dengan tabel Barang";
            }
        } else {
            // Jika tidak ada data yang berhasil diimpor
            $finalMessage .= 'Error: Tidak ada data yang berhasil diimpor.';
        }
    
        // Simpan pesan dalam session
        session()->flash('finalMessage', $finalMessage);
    
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