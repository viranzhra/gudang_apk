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
use App\Models\Supplier;

class BarangController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

		$data = DB::table('barang')
			->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
			->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
			->leftJoin('barang_masuk', 'barang.id', '=', 'barang_masuk.barang_id')
			->select('barang.*', 'jenis_barang.nama as nama_jenis_barang', 'supplier.nama as nama_supplier', DB::raw('SUM(barang_masuk.jumlah) as jumlah'))
			->when($search, function ($query) use ($search) {
				return $query->where('barang.nama', 'like', '%' . $search . '%')
				->orWhere('jenis_barang.nama', 'like', '%' . $search . '%')
				->orWhere('supplier.nama', 'like', '%' . $search . '%');
			})
			->groupBy('barang.id', 'barang.nama', 'barang.jenis_barang_id', 'barang.supplier_id', 'barang.keterangan', 'barang.created_at', 'barang.updated_at', 'jenis_barang.nama', 'supplier.nama')
			->orderBy('jumlah', 'desc')
			->paginate(7);
			
		return view('barang.index', compact('data'));
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
			'supplier_id' => 'required|numeric',
			//'status' => 'required|in:Baik,Rusak',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama barang harus diisi.',
			'nama.string' => 'Nama barang harus berupa teks.',
			'nama.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
			'jenis_barang.required' => 'Jenis barang harus dipilih.',
			'jenis_barang.numeric' => 'Jenis barang harus berupa angka.',
			'supplier_id.required' => 'Supplier harus dipilih.',
			'supplier_id.numeric' => 'Supplier harus dipilih.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);
		
		//$userId = Auth::id();

		$data = Barang::create([
			'nama' => $request->nama,
			'jenis_barang_id' => $request->jenis_barang,
			'supplier_id' => $request->supplier_id,
			//'jumlah' => 0,
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
			'supplier_id' => 'required|numeric',
			//'status' => 'required|in:Baik,Rusak',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama barang harus diisi.',
			'nama.string' => 'Nama barang harus berupa teks.',
			'nama.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
			'jenis_barang.required' => 'Jenis barang harus dipilih.',
			'jenis_barang.numeric' => 'Jenis barang harus berupa angka.',
			'supplier_id.required' => 'Supplier harus dipilih.',
			'supplier_id.numeric' => 'Supplier harus dipilih.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);

		$data = Barang::find($id);

		$data->nama = $request->nama;
		$data->jenis_barang_id = $request->jenis_barang;
		$data->supplier_id = $request->supplier_id;
		//$data->status = $request->status;
		$data->keterangan = $request->keterangan;

		$data->save();

		return redirect('/barang')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function detail($id)
	{
		$barang = DB::table('barang')
			->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
			->select('barang.*', 'jenis_barang.nama as jenis_nama')
			->where('barang.id', $id)
			->first();
	
		if (!$barang) {
			return response()->json(['message' => 'Data tidak ditemukan!'], 404);
		}
	
		return response()->json([
			'nama' => $barang->nama,
			'jenis' => $barang->jenis_nama,
			'jumlah' => $barang->jumlah,
			'keterangan' => $barang->keterangan,
		]);
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
