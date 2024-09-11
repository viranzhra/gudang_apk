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
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{

	public function index(Request $request)
	{
		if ($request->ajax()) {
			try {
				$data = Supplier::select('id', 'nama', 'alamat', 'telepon', 'keterangan')->latest()->get();
				return DataTables::of($data)
					->addIndexColumn() // This adds a row index
					->addColumn('checkbox', function($row){
						return '<input type="checkbox" class="select-item" value="'.$row->id.'">';
					})
					->addColumn('action', function ($row) {
						return '<a href="/supplier/edit/' . $row->id . '" class="btn-edit btn-action" aria-label="Edit">
									<iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
								</a>'
							. '<button data-id="' . $row->id . '" class="btn-action btn-delete" aria-label="Delete">
									<iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
								</button>';
					})
					->rawColumns(['checkbox', 'action']) // Allow HTML for these columns
					->make(true);
			} catch (\Exception $e) {
				\Log::error('Error fetching data: '.$e->getMessage());
				return response()->json(['error' => 'Internal Server Error'], 500);
			}
		}
	
		return view('supplier.index');
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
			'telepon' => 'required|regex:/^[0-9\s]{10,15}$/',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama harus diisi.',
			'nama.string' => 'Nama harus berupa teks.',
			'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
			'alamat.required' => 'Alamat harus diisi.',
			'alamat.string' => 'Alamat harus berupa teks.',
			'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
			'telepon.required' => 'Nomor telepon harus diisi.',
			'telepon.regex' => 'Nomor telepon hanya boleh berisi spasi dan angka dengan panjang 10 sampai 15',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
		]);

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
			'telepon' => 'required|regex:/^[0-9\s]{10,15}$/',
			'keterangan' => 'nullable|string|max:255',
		], [
			'nama.required' => 'Nama harus diisi.',
			'nama.string' => 'Nama harus berupa teks.',
			'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
			'alamat.required' => 'Alamat harus diisi.',
			'alamat.string' => 'Alamat harus berupa teks.',
			'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
			'telepon.required' => 'Nomor telepon harus diisi.',
			'telepon.regex' => 'Nomor telepon hanya boleh berisi spasi dan angka dengan panjang 10 sampai 15',
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
		$customer = Supplier::find($id);

		$customer->delete();
		return redirect('/supplier')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$supplier = Supplier::find($id);
			if ($supplier) {
				$supplier->delete();
			}
		}
		return redirect('/supplier')->with('success', 'Anda berhasil menghapus data terpilih!');
	}
}
