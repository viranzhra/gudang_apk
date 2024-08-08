<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\SerialNumber;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{

	public function index(Request $request)
	{
		$search = $request->input('search');

		$barangMasuk = DB::table('barang_masuk')
			->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
			->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
			->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
			->select(
				'barang_masuk.id as barang_masuk_id',
				'barang_masuk.keterangan',
				'barang_masuk.tanggal',
				'barang.nama as nama_barang',
				'jenis_barang.nama as nama_jenis_barang',
				'supplier.nama as nama_supplier',
				'barang_masuk.jumlah'
			)
			->selectRaw("DATE_FORMAT(barang_masuk.tanggal, '%d %M %Y') as formatted_tanggal")
			->when($search, function ($query) use ($search) {
				return $query->where('barang_masuk.keterangan', 'like', '%' . $search . '%')
					->orWhere('barang.nama', 'like', '%' . $search . '%')
					->orWhere('barang_masuk.tanggal', 'like', '%' . $search . '%');
			})
			->orderBy('barang_masuk.created_at', 'desc')
			->paginate(7);

		// Ambil detail barang masuk
		foreach ($barangMasuk as $item) {
			$item->detail = DB::table('detail_barang_masuk')
				->leftJoin('serial_number', 'detail_barang_masuk.serial_number_id', '=', 'serial_number.id')
				->leftJoin('status_barang', 'detail_barang_masuk.status_barang_id', '=', 'status_barang.id')
				->select('serial_number.serial_number', 'status_barang.nama as status_barang', 'status_barang.warna as warna_status_barang', 'detail_barang_masuk.kelengkapan')
				->where('detail_barang_masuk.barangmasuk_id', $item->barang_masuk_id)
				->orderBy('serial_number.serial_number', 'asc')
				->get();
		}

		// Transform data untuk modal
		$barangMasuk->getCollection()->transform(function ($item) {
			$item->tanggal = \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY');
			return $item;
		});

		return view('barangmasuk.index', compact('barangMasuk'));
	}


	public function create($id = null)
	{
		$barangMasuk = null;
		$jenis_barang_id = null;
		$barangbyjenis = null;
		$jenis_barang = DB::table('jenis_barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$barang = DB::table('barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();

		if ($id !== null) {
			$barangMasuk = DB::table('barang_masuk')->where('id', $id)->first();
			$jenis_barang_id = DB::table('barang')
				->join('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
				->where('barang.id', $barangMasuk->barang_id)
				->value('jenis_barang.id');
			$barangbyjenis = DB::table('barang')->where('jenis_barang_id', $jenis_barang_id)->orderBy('nama', 'asc')->get();
		}
		
		$supplier = DB::table('supplier')->select('id', 'nama')->orderBy('nama', 'asc')->get();
		$status_barang = DB::table('status_barang')->select('id', 'nama')->orderBy('nama', 'asc')->get();

		/*$bm_kode = DB::table('barang_masuk')->orderBy('id', 'desc')->value('bm_kode');

		if ($bm_kode) {
			$angkaTerakhir = intval(substr($bm_kode, 3));
			$angkaSelanjutnya = $angkaTerakhir + 1;
			$bm_kode_value = 'BM_' . str_pad($angkaSelanjutnya, 3, '0', STR_PAD_LEFT);
		} else {
			$bm_kode_value = 'BM_' . str_pad(1, 3, '0', STR_PAD_LEFT);
		}*/
		$bm_kode_value = NULL;

		return view('barangmasuk.create', compact('barangMasuk', 'supplier', 'barang', 'barangbyjenis', 'jenis_barang', 'jenis_barang_id', 'status_barang', 'bm_kode_value'));
	}

	public function getBarangByJenis($id)
	{
		$barang = DB::table('barang')->where('jenis_barang_id', $id)->orderBy('nama', 'asc')->get();
		return response()->json($barang);
	}

	/*public function createSelected($id)
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
	}*/

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'barang_id' => 'required|numeric',
			'keterangan' => 'nullable|string|max:255',
			'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
			'serial_numbers.*' => 'required|string|max:255|unique:serial_number,serial_number',
			'status_barangs.*' => 'required|exists:status_barang,id',
			'kelengkapans.*' => 'nullable|string|max:255',
		], [
			'barang_id.required' => 'Barang harus dipilih.',
			'barang_id.numeric' => 'Barang harus dipilih.',
			'keterangan.string' => 'Keterangan harus berupa teks.',
			'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter.',
			'tanggal.required' => 'Tanggal harus diisi.',
			'tanggal.date_format' => 'Format tanggal harus YYYY-MM-DD.',
			'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
			'serial_numbers.*.required' => 'Serial Number harus diisi.',
			'serial_numbers.*.string' => 'Serial Number harus berupa teks.',
			'serial_numbers.*.max' => 'Serial Number tidak boleh lebih dari 255 karakter.',
			'serial_numbers.*.unique' => 'Serial Number sudah digunakan.',
			'status_barangs.*.required' => 'Kondisi Barang harus dipilih.',
			'status_barangs.*.exists' => 'Kondisi Barang yang dipilih tidak valid.',
			'kelengkapans.string' => 'Kelengkapan harus berupa teks.',
			'kelengkapans.max' => 'Kelengkapan tidak boleh lebih dari 255 karakter.',
        ]); 
 
		$barangMasuk = BarangMasuk::create([
			'barang_id' => $request->barang_id,
			'jumlah' => count($request->serial_numbers),
			'keterangan' => $request->keterangan,
			'tanggal' => $request->tanggal,
		]);

		/*$barang = Barang::find($request->barang_id);
		$barang->jumlah += $barangMasuk->jumlah;
		$barang->save();*/

		foreach ($request->serial_numbers as $index => $serialNumber) {
			// Simpan data serial number
			$serial = SerialNumber::create([
				'serial_number' => $serialNumber,
				'barangmasuk_id' => $barangMasuk->id,
			]);
	
			// Simpan detail barang masuk
			DetailBarangMasuk::create([
				'barangmasuk_id' => $barangMasuk->id,
				'serial_number_id' => $serial->id,
				'status_barang_id' => $request->status_barangs[$index],
				'kelengkapan' => $request->kelengkapans[$index],
			]);
		}

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
		
			if ($data) {
				// Temukan semua serial number yang terkait dengan barang masuk ini
				$serialNumbers = SerialNumber::where('barangmasuk_id', $data->id)->get();
		
				// Hapus detail barang masuk yang terkait
				foreach ($serialNumbers as $serialNumber) {
					DetailBarangMasuk::where('serial_number_id', $serialNumber->id)->delete();
					$serialNumber->delete();
				}
		
				// Update jumlah barang di tabel barang
				/*$barang = Barang::find($data->barang_id);
				$barang->jumlah -= $data->jumlah;
				$barang->save();*/
		
				// Hapus barang masuk
				$data->delete();
		
				return redirect('/barangmasuk')->with('success', 'Anda berhasil menghapus data!');
			} else {
				return redirect('/barangmasuk')->with('error', 'Data tidak ditemukan!');
			}
		}

		public function deleteSelected(Request $request)
		{
			$ids = $request->input('ids');

			foreach ($ids as $id) {
				$data = BarangMasuk::find($id);

				if ($data) {
					// Temukan semua serial number yang terkait dengan barang masuk ini
					$serialNumbers = SerialNumber::where('barangmasuk_id', $data->id)->get();

					// Hapus detail barang masuk yang terkait
					foreach ($serialNumbers as $serialNumber) {
						DetailBarangMasuk::where('serial_number_id', $serialNumber->id)->delete();
						$serialNumber->delete();
					}

					// Update jumlah barang di tabel barang
					/*$barang = Barang::find($data->barang_id);
					$barang->jumlah -= $data->jumlah;
					$barang->save();*/

					// Hapus barang masuk
					$data->delete();
				}
			}

			return response()->json(['success' => 'Data berhasil dihapus']);
		}

}
