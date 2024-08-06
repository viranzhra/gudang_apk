<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\JenisBarang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JenisBarangController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

        $data = JenisBarang::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })->paginate(7);

        return view('jenisbarang.index', compact('data'));
	}

	public function create()
	{
		return view('jenisbarang.create');
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
		
		$data = JenisBarang::create([
			'nama' => $request->nama,
		]);

		return redirect('/jenisbarang')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$data = JenisBarang::find($id);
		return view('jenisbarang.edit', ['data' => $data]);
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

		$data = JenisBarang::find($id);

		$data->nama = $request->nama;
		$data->save();

		return redirect('/jenisbarang')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$data = JenisBarang::find($id);

		$data->delete();
		return redirect('/jenisbarang')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$data = JenisBarang::find($id);
			if ($data) {
				$data->delete();
			}
		}
		return redirect('/jenisbarang')->with('success', 'Anda berhasil menghapus data terpilih!');
	}
}
