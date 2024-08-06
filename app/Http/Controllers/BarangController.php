<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JenisBarang;

class BarangController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

		$data = DB::table('barang')
			->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
			->select('barang.*', 'jenis_barang.nama as nama_jenis_barang')
			->when($search, function ($query) use ($search) {
				return $query->where('barang.nama', 'like', '%' . $search . '%')
				->orWhere('jenis_barang.nama', 'like', '%' . $search . '%');
			})
			->orderBy('barang.jumlah', 'desc')
			->paginate(7);
		
		// Fetch jenis_barang data
		$jenis_barang = JenisBarang::all();

		return view('barang.index', [
			'data' => $data,
			'jenis_barang' => $jenis_barang,
		]);
	}

	public function create()
	{
		// $jenis_barang = DB::table('jenis_barang')->select('id', 'nama')->get();
		return view('barang.create', compact('jenis_barang'));
	}

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
			'jenis_barang' => 'required|numeric',
			//'status' => 'required|in:Baik,Rusak',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama barang harus diisi.',
			'nama.string' => 'Nama barang harus berupa teks.',
			'nama.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
			'jenis_barang.required' => 'Jenis barang harus dipilih.',
			'jenis_barang.numeric' => 'Jenis barang harus berupa angka.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);
		
		//$userId = Auth::id();

		Barang::create([
			'nama' => $request->nama,
			'jenis_barang_id' => $request->jenis_barang,
			'jumlah' => 0,
			//'status' => $request->status,
			'keterangan' => $request->keterangan,
		]);

		return redirect('/barang')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$jenis_barang = DB::table('jenis_barang')->select('id', 'nama')->get();
		$data = Barang::find($id);
		return view('barang.edit', compact('data','jenis_barang'));
	}

	public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
			'jenis_barang' => 'required|numeric',
			//'status' => 'required|in:Baik,Rusak',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama barang harus diisi.',
			'nama.string' => 'Nama barang harus berupa teks.',
			'nama.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
			'jenis_barang.required' => 'Jenis barang harus dipilih.',
			'jenis_barang.numeric' => 'Jenis barang harus berupa angka.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);

		$data = Barang::find($id);

		$data->nama = $request->nama;
		$data->jenis_barang_id = $request->jenis_barang;
		//$data->status = $request->status;
		$data->keterangan = $request->keterangan;

		$data->save();

		return redirect('/barang')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$data = Barang::find($id);

		if (!$data) {
			return redirect('/barang')->with('error', 'Data tidak ditemukan!');
		}

		// Hapus semua entri terkait di tabel BarangMasuk
		BarangMasuk::where('barang_id', $id)->delete();

		// Hapus data barang
		$data->delete();

		// Redirect dengan pesan sukses
		return redirect('/barang')->with('success', 'Anda berhasil menghapus data!');
	}


	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$data = Barang::find($id);
			if ($data) {
				BarangMasuk::where('barang_id', $id)->delete();
				$data->delete();
			}
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
