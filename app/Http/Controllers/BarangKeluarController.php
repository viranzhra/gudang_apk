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
            ->leftJoin('customer', 'permintaan_barang_keluar.customer_id', '=', 'customer.id')
            ->leftJoin('keperluan', 'permintaan_barang_keluar.keperluan_id', '=', 'keperluan.id')
            ->select(
                'barang_keluar.*',
                'customer.nama as nama_customer',
                'keperluan.nama as nama_keperluan',
                'permintaan_barang_keluar.id as permintaan_barang_keluar_id',
                'permintaan_barang_keluar.jumlah',
                'keperluan.extend as extend',
                DB::raw("REPLACE(keperluan.nama_tanggal_awal, 'Tanggal ', '') as nama_tanggal_awal"),
                DB::raw("REPLACE(keperluan.batas_akhir, 'Tanggal ', '') as batas_akhir")
            )
            ->selectRaw("TO_CHAR(permintaan_barang_keluar.tanggal_awal, 'DD Mon YYYY') as tanggal_awal")
            ->selectRaw("TO_CHAR(permintaan_barang_keluar.tanggal_akhir, 'DD Mon YYYY') as tanggal_akhir")
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('customer.nama', 'like', '%' . $search . '%')
                        ->orWhere('keperluan.nama', 'like', '%' . $search . '%')
                        ->orWhere('permintaan_barang_keluar.jumlah', 'like', '%' . $search . '%')
                        ->orWhere('barang_keluar.tanggal_awal', 'like', '%' . $search . '%');
                });
            })            
            ->orderBy('barang_keluar.created_at', 'desc')
            ->paginate(7);

        foreach ($data as $item) {
            $item->detail = DB::table('detail_permintaan_bk')
                ->leftJoin('serial_number', 'detail_permintaan_bk.serial_number_id', '=', 'serial_number.id')
                ->leftJoin('barang_masuk', 'serial_number.barangmasuk_id', '=', 'barang_masuk.id')
                ->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
                ->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
                ->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
                ->select(
                    'serial_number.serial_number',
                    'barang.nama as nama_barang',
                    'jenis_barang.nama as nama_jenis_barang',
                    'supplier.nama as nama_supplier'
                )
                ->where('detail_permintaan_bk.permintaan_barang_keluar_id', $item->permintaan_barang_keluar_id)
                ->orderBy('serial_number.serial_number', 'asc')
                ->get();
        }

        // Format tanggal untuk tampilan
        $data->getCollection()->transform(function ($item) {
            $item->tanggal_awal = \Carbon\Carbon::parse($item->tanggal_awal)->isoFormat('DD MMMM YYYY');
            $item->tanggal_akhir = \Carbon\Carbon::parse($item->tanggal_akhir)->isoFormat('DD MMMM YYYY');
            return $item;
        });        

        return view('barangkeluar.index', compact('data'));
    }

}
