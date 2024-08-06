<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

		$data = DB::table('barang_masuk')
        ->leftJoin('supplier', 'barang_masuk.supplier_id', '=', 'supplier.id')
        ->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
        ->leftJoin('status_barang', 'barang_masuk.status_barang_id', '=', 'status_barang.id')
        ->select('barang_masuk.*', 'supplier.nama as nama_supplier', 'barang.nama as nama_barang', 'status_barang.nama as nama_status_barang')
        ->selectRaw("DATE_FORMAT(barang_masuk.tanggal, '%d %M %Y') as formatted_tanggal")
        ->when($search, function ($query) use ($search) {
            return $query->where('barang_masuk.barang_id', 'like', '%' . $search . '%')
                ->orWhere('barang.nama', 'like', '%' . $search . '%')
                ->orWhere('supplier.nama', 'like', '%' . $search . '%')
                ->orWhere('status_barang.nama', 'like', '%' . $search . '%')
                ->orWhere('barang_masuk.serial_number', 'like', '%' . $search . '%');
        })
        ->paginate(7);

		$data->getCollection()->transform(function ($item) {
			$item->tanggal = \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY');
			return $item;
		});

        return view('barangmasuk.index', compact('data'));
	}

	public function create()
	{
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

		return view('barangmasuk.create', compact('supplier','barang','jenis_barang','status_barang','bm_kode_value'));
	}

	public function getBarangByJenis($id)
	{
		$barang = DB::table('barang')->where('jenis_barang_id', $id)->orderBy('nama', 'asc')->get();
		return response()->json($barang);
	}

	public function createSelected($id)
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
	}

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'serial_number' => 'required|numeric|unique:barang_masuk,serial_number',
			'supplier_id' => 'required|numeric',
			'barang_id' => 'required|numeric',
			'status_barang_id' => 'required|numeric',
			//'jumlah' => 'required|numeric|max:255',
			'keterangan' => 'nullable|string|max:255',
			'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
		], [
			'serial_number.required' => 'Serial Number harus diisi.',
			'serial_number.numeric' => 'Serial Number harus berupa angka.',
			'serial_number.unique' => 'Serial Number sudah digunakan.',
			'supplier_id.required' => 'Supplier harus dipilih.',
			'supplier_id.numeric' => 'Supplier harus dipilih.',
			'barang_id.required' => 'Barang harus dipilih.',
			'barang_id.numeric' => 'Barang harus dipilih.',
			'status_barang_id.required' => 'Kondisi barang harus dipilih.',
			'status_barang_id.numeric' => 'Kondisi barang harus dipilih.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
			'tanggal.required' => 'Tanggal harus diisi.',
			'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
			'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
        ]);
		
		$bm_kode = DB::table('barang_masuk')->orderBy('id', 'desc')->value('bm_kode');

		if ($bm_kode) {
			$angkaTerakhir = intval(substr($bm_kode, 3));
			$angkaSelanjutnya = $angkaTerakhir + 1;
			$bm_kode_value = 'BM_' . str_pad($angkaSelanjutnya, 3, '0', STR_PAD_LEFT);
		} else {
			$bm_kode_value = 'BM_' . str_pad(1, 3, '0', STR_PAD_LEFT);
		}

		$data = BarangMasuk::create([
			'bm_kode' => $bm_kode_value,
			'serial_number' => $request->serial_number,
			'supplier_id' => $request->supplier_id,
			'barang_id' => $request->barang_id,
			'status_barang_id' => $request->status_barang_id,
			//'jumlah' => $request->jumlah,
			'jumlah' => 1,
			'keterangan' => $request->keterangan,
			'tanggal' => $request->tanggal,
		]);

		$barang = Barang::find($request->barang_id);
		$barang->jumlah += 1; //$request->jumlah;
		$barang->save();

		return redirect('/barangmasuk')->with('success', 'Anda berhasil menambahkan data!');
	}

	/*public function edit($id)
	{
		$supplier = DB::table('supplier')->select('id', 'nama')->get();
		$barang = DB::table('barang')->select('id', 'nama')->get();

		$data = DB::table('barang_masuk')
			->leftJoin('supplier', 'barang_masuk.supplier_id', '=', 'supplier.id')
			->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
			->select('barang_masuk.*', 'supplier.nama as nama_supplier', 'barang.nama as nama_barang')
			->where('barang_masuk.id', '=', $id)
			->first();

		return view('barangmasuk.edit', compact('supplier', 'barang', 'data'));
	}

	public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'bm_kode' => 'required|string',
			'serial_number' => 'required|numeric',
			'supplier_id' => 'required|numeric',
			'barang_id' => 'required|numeric',
			//'jumlah' => 'required|numeric|max:255',
            'keterangan' => 'string|max:255',
            'tanggal' => 'required|date_format:Y-m-d H:i:s',
        ]);

		$data = Barang::find($id);

		$data->bm_kode = $request->bm_kode;
		$data->serial_number = $request->serial_number;
		$data->supplier_id = $request->supplier_id;
		$data->barang_id = $request->barang_id;
		//$data->jumlah = $request->jumlah;
		$data->keterangan = $request->keterangan;
		$data->tanggal = $request->tanggal;

		$data->save();

		return redirect('/barangmasuk')->with('success', 'Anda berhasil memperbarui data!');
	}
		*/

	public function delete($id)
	{
		$data = BarangMasuk::find($id);
		$barang = Barang::find($data->barang_id);
		$barang->jumlah -= $data->jumlah;
		$barang->save();

		$data->delete();
		return redirect('/barangmasuk')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$data = BarangMasuk::find($id);
			if ($data) {
				$barang = Barang::find($data->barang_id);
				$barang->jumlah -= $data->jumlah;
				$barang->save();
				$data->delete();
			}
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
