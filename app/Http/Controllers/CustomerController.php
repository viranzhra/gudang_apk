<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Customer;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

        $data = Customer::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
			->orWhere('alamat', 'like', '%' . $search . '%')
			->orWhere('telepon', 'like', '%' . $search . '%')
			->orWhere('keterangan', 'like', '%' . $search . '%');
        })->orderBy('nama', 'asc')->paginate(4);	
					
        return view('customer.index', compact('data'));
	}

	public function create()
	{
		return view('customer.create');
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

		//$userId = Auth::id();

		$data = Customer::create([
			'nama' => $request->nama,
			'alamat' => $request->alamat,
			'telepon' => $request->telepon,
			'keterangan' => $request->keterangan,
		]);

		return redirect('/customer')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$data = Customer::find($id);
		return view('customer.edit', ['data' => $data]);
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

		$data = Customer::find($id);

		$data->nama = $request->nama;
		$data->alamat = $request->alamat;
		$data->telepon = $request->telepon;
		$data->keterangan = $request->keterangan;
		$data->save();

		return redirect('/customer')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$customer = Customer::find($id);

		/*$barangMasuk = BarangMasuk::where('customer_id', $id)->get();

		foreach ($barangMasuk as $item) {
			$barang = Barang::find($item->barang_id);
			if ($barang) {
				$barang->jumlah -= $item->jumlah;
				$barang->save();
			}
			$item->delete();
		}*/

		$customer->delete();
		return redirect('/customer')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$customer = Customer::find($id);

			if ($customer) {
				/*$barangMasuk = BarangMasuk::where('customer_id', $id)->get();

				foreach ($barangMasuk as $item) {
					$barang = Barang::find($item->barang_id);
					if ($barang) {
						$barang->jumlah -= $item->jumlah;
						$barang->save();
					}
					$item->delete();
				}*/

				$customer->delete();
			}
		}
		return redirect('/customer')->with('success', 'Anda berhasil menghapus data terpilih!');
	}
}
