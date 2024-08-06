<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Keperluan;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeperluanController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

        $data = Keperluan::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })->paginate(7);

        return view('keperluan.index', compact('data'));
	}

	public function create()
	{
		return view('keperluan.create');
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

		$data = Keperluan::create([
			'nama' => $request->nama,
		]);

		return redirect('/keperluan')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$data = Keperluan::find($id);
		return view('keperluan.edit', ['data' => $data]);
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

		$data = Keperluan::find($id);

		$data->nama = $request->nama;
		$data->save();

		return redirect('/keperluan')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$keperluan = Keperluan::find($id);
		/*$barangMasuk = BarangMasuk::where('status_barang_id', $id)->get();

		foreach ($barangMasuk as $item) {
			$barang = Barang::find($item->barang_id);
			if ($barang) {
				$barang->jumlah -= $item->jumlah;
				$barang->save();
			}
			$item->delete();
		}*/

		$keperluan->delete();
		return redirect('/keperluan')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$keperluan = Keperluan::find($id);
			/*$barangMasuk = BarangMasuk::where('status_barang_id', $id)->get();

			foreach ($barangMasuk as $item) {
				$barang = Barang::find($item->barang_id);
				if ($barang) {
					$barang->jumlah -= $item->jumlah;
					$barang->save();
				}
				$item->delete();
			}*/

			$keperluan->delete();
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
