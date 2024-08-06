<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
	{
		$search = $request->input('search');

		$data = DB::table('barang_keluar')
        ->leftJoin('permintaan_barang_keluar', 'barang_keluar.permintaan_id', '=', 'permintaan_barang_keluar.id')
        ->leftJoin('barang_masuk', 'permintaan_barang_keluar.barangmasuk_id', '=', 'barang_masuk.id')
        ->leftJoin('customer', 'permintaan_barang_keluar.customer_id', '=', 'customer.id')
        ->leftJoin('keperluan', 'permintaan_barang_keluar.keperluan_id', '=', 'keperluan.id')
        ->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
        ->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
        ->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
        ->select('barang_keluar.*', 'barang_masuk.serial_number as serial_number', 'jenis_barang.nama as nama_jenis_barang', 'supplier.nama as nama_supplier', 'barang.nama as nama_barang', 'customer.nama as nama_customer', 'keperluan.nama as nama_keperluan')
        ->selectRaw("DATE_FORMAT(barang_keluar.tanggal, '%d %M %Y') as formatted_tanggal")
        ->when($search, function ($query) use ($search) {
            return $query->where('barang.nama', 'like', '%' . $search . '%')
                ->orWhere('customer.nama', 'like', '%' . $search . '%')
                ->orWhere('keperluan.nama', 'like', '%' . $search . '%')
                ->orWhere('barang_masuk.serial_number', 'like', '%' . $search . '%')
                ->orWhere('barang_keluar.tanggal', 'like', '%' . $search . '%');
        })
        ->orderBy('barang_keluar.created_at', 'desc')
        //->orderBy('barang_masuk.serial_number', 'desc')
        ->paginate(7);

		$data->getCollection()->transform(function ($item) {
			$item->tanggal = \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY');
			return $item;
		});

        return view('barangkeluar.index', compact('data'));
	}
}
