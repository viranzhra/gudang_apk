<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

        $data = Supplier::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
			->orWhere('alamat', 'like', '%' . $search . '%')
			->orWhere('telepon', 'like', '%' . $search . '%')
			->orWhere('keterangan', 'like', '%' . $search . '%');
        })->paginate(7);
				
        return view('supplier.index', compact('data'));
	}

	public function create()
	{
		return view('supplier.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
			'alamat' => 'required|string|max:255',
			'telepon' => 'required|numeric|digits_between:10,15',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama harus diisi.',
			'nama.string' => 'Nama harus berupa teks.',
			'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
			'alamat.required' => 'Alamat harus diisi.',
			'alamat.string' => 'Alamat harus berupa teks.',
			'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
			'telepon.required' => 'Nomor telepon harus diisi.',
			'telepon.numeric' => 'Nomor telepon harus berupa angka.',
			'telepon.digits_between' => 'Nomor telepon harus memiliki panjang antara 10 sampai 15 digit.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);

		//$userId = Auth::id();

		$data = Supplier::create([
			'nama' => $request->nama,
			'alamat' => $request->alamat,
			'telepon' => $request->telepon,
			'keterangan' => $request->keterangan,
		]);

		return redirect('/supplier')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$data = Supplier::find($id);
		return view('supplier.edit', ['data' => $data]);
	}

	public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
			'alamat' => 'required|string|max:255',
			'telepon' => 'required|numeric|digits_between:10,15',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama harus diisi.',
			'nama.string' => 'Nama harus berupa teks.',
			'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
			'alamat.required' => 'Alamat harus diisi.',
			'alamat.string' => 'Alamat harus berupa teks.',
			'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
			'telepon.required' => 'Nomor telepon harus diisi.',
			'telepon.numeric' => 'Nomor telepon harus berupa angka.',
			'telepon.digits_between' => 'Nomor telepon harus memiliki panjang antara 10 sampai 15 digit.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);

		$data = Supplier::find($id);

		$data->nama = $request->nama;
		$data->alamat = $request->alamat;
		$data->telepon = $request->telepon;
		$data->keterangan = $request->keterangan;
		$data->save();

		return redirect('/supplier')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$supplier = Supplier::find($id);

		$barangMasuk = BarangMasuk::where('supplier_id', $id)->get();

		foreach ($barangMasuk as $item) {
			$barang = Barang::find($item->barang_id);
			if ($barang) {
				$barang->jumlah -= $item->jumlah;
				$barang->save();
			}
			$item->delete();
		}

		$supplier->delete();
		return redirect('/supplier')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$supplier = Supplier::find($id);

			if ($supplier) {
				$barangMasuk = BarangMasuk::where('supplier_id', $id)->get();

				foreach ($barangMasuk as $item) {
					$barang = Barang::find($item->barang_id);
					if ($barang) {
						$barang->jumlah -= $item->jumlah;
						$barang->save();
					}
					$item->delete();
				}

				$supplier->delete();
			}
		}
		return redirect('/supplier')->with('success', 'Anda berhasil menghapus data terpilih!');
	}
}
