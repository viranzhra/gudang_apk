<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\StatusBarang;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatusBarangController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

        $data = StatusBarang::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })->paginate(7);

        return view('statusbarang.index', compact('data'));
	}

	public function create()
	{
		return view('statusbarang.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
		], [
			'nama.required' => 'Nama jenis barang harus diisi.',
			'nama.string' => 'Nama jenis barang harus berupa teks.',
			'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
		]);

		$data = StatusBarang::create([
			'nama' => $request->nama,
		]);

		return redirect('/statusbarang')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$data = StatusBarang::find($id);
		return view('statusbarang.edit', ['data' => $data]);
	}

	public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
		], [
			'nama.required' => 'Nama jenis barang harus diisi.',
			'nama.string' => 'Nama jenis barang harus berupa teks.',
			'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
		]);

		$data = StatusBarang::find($id);

		$data->nama = $request->nama;
		$data->save();

		return redirect('/statusbarang')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$statusBarang = StatusBarang::find($id);
		$barangMasuk = BarangMasuk::where('status_barang_id', $id)->get();

		foreach ($barangMasuk as $item) {
			$barang = Barang::find($item->barang_id);
			if ($barang) {
				$barang->jumlah -= $item->jumlah;
				$barang->save();
			}
			$item->delete();
		}

		$statusBarang->delete();
		return redirect('/statusbarang')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$statusBarang = StatusBarang::find($id);
			$barangMasuk = BarangMasuk::where('status_barang_id', $id)->get();

			foreach ($barangMasuk as $item) {
				$barang = Barang::find($item->barang_id);
				if ($barang) {
					$barang->jumlah -= $item->jumlah;
					$barang->save();
				}
				$item->delete();
			}

			$statusBarang->delete();
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
