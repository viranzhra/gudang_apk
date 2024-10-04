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
        $filePath = public_path('templates/template.xlsx');
        return response()->download($filePath);
    }

    public function uploadExcel(Request $request)
	{
		$request->validate([
			'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
		]);

		$file = $request->file('file');

		Excel::import(new BarangMasukImport, $file);

		return redirect('/barangmasuk')->with('success', 'Data berhasil diunggah!');
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
