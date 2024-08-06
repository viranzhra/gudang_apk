<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\SerialNumber;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SerialNumberController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

		$data = DB::table('serial_number')
			->leftJoin('barang', 'serial_number.barangmasuk_id', '=', 'barang.id')
			->select('serial_number.*', 'barang.nama as nama_barang')
			->when($search, function ($query) use ($search) {
				return $query->where('serial_number.serial_number', 'like', '%' . $search . '%')
				->orWhere('barang.nama', 'like', '%' . $search . '%');
			})
			->orderBy('serial_number.serial_number', 'asc')
			->paginate(7);
			
        return view('serialnumber.index', compact('data'));
	}

	public function create()
	{
		$jenis_barang = DB::table('jenis_barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$barang = DB::table('barang')->select('id', 'nama')->get();
		return view('serialnumber.create', compact('jenis_barang','barang'));
	}

	public function getBarangByJenis($id)
	{
		$barang = DB::table('barang')->where('jenis_barang_id', $id)->orderBy('nama', 'asc')->get();
		return response()->json($barang);
	}

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'barangmasuk_id' => 'required|numeric',
			'serial_number' => 'required|numeric',
		], [
			'barangmasuk_id.required' => 'Barang harus dipilih.',
			'barangmasuk_id.numeric' => 'Barang harus dipilih.',
			'serial_number.required' => 'SN harus diisi.',
			'serial_number.numeric' => 'SN harus berupa angka.',
		]);
		
		$data = SerialNumber::create([
			'serial_number' => $request->serial_number,
			'barangmasuk_id' => $request->barangmasuk_id,
		]);

		return redirect('/serialnumber')->with('success', 'Anda berhasil menambahkan data!');
	}

	public function edit($id)
	{
		$barang = DB::table('barang')->select('id', 'nama')->get();
		$data = Barang::find($id);
		return view('serialnumber.edit', compact('data','barang'));
	}

	public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'barangmasuk_id' => 'required|numeric',
			'serial_number' => 'required|numeric',
		], [
			'barangmasuk_id.required' => 'Barang harus dipilih.',
			'barangmasuk_id.numeric' => 'Barang harus dipilih.',
			'serial_number.required' => 'SN harus diisi.',
			'serial_number.numeric' => 'SN harus berupa angka.',
		]);

		$data = SerialNumber::find($id);

		$data->barangmasuk_id = $request->barangmasuk_id;
		$data->serial_number = $request->serial_number;

		$data->save();

		return redirect('/serialnumber')->with('success', 'Anda berhasil memperbarui data!');
	}

	public function delete($id)
	{
		$data = SerialNumber::find($id);
		$data->delete();
		return redirect('/serialnumber')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$data = SerialNumber::find($id);
			if ($data) {
				$data->delete();
			}
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
