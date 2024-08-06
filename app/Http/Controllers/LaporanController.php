<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{

    public function stok(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('barang_masuk')
            ->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
            ->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
            ->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
            ->leftJoin('status_barang', 'barang_masuk.status_barang_id', '=', 'status_barang.id')
            ->select(
                'barang_masuk.barang_id',
                'barang.id as valbarang_id',
                'barang.nama as nama_barang',
                'barang.jumlah as jumlah_barang',
                'barang.keterangan as keterangan_barang',
                'jenis_barang.nama as nama_jenis_barang',
                'supplier.nama as supplier_name',
                'barang_masuk.serial_number',
                'status_barang.nama as status_name',
                'status_barang.warna as warna_status'
            )
            ->where('barang.jumlah', '>', 0)
            ->when($search, function ($query) use ($search) {
                return $query->where('barang.nama', 'like', '%' . $search . '%')
                    ->orWhere('jenis_barang.nama', 'like', '%' . $search . '%');
            })
            ->orderBy('barang.jumlah','desc')
            ->orderBy('barang.nama','asc');

        $data = $query->get();

        // Memproses data ke grup Serial Number berdasarkan Supplier dan Kondisi Barang
        $groupedData = $data->groupBy('barang_id')->map(function ($items) {
            $result = [
                'barang_id' => $items->first()->barang_id,
                'nama_barang' => $items->first()->nama_barang,
                'nama_jenis_barang' => $items->first()->nama_jenis_barang,
                'jumlah_barang' => $items->first()->jumlah_barang,
                'keterangan_barang' => $items->first()->keterangan_barang,
                'suppliers' => []
            ];

            foreach ($items as $item) {
                if (!isset($result['suppliers'][$item->supplier_name])) {
                    $result['suppliers'][$item->supplier_name] = [];
                }
                $result['suppliers'][$item->supplier_name][] = [
                    'serial_number' => $item->serial_number,
                    'nama_status' => $item->status_name,
                    'warna_status' => $item->warna_status
                ];
            }

            return $result;
        });

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedData->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 7),
            $groupedData->count(),
            7,
            null,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('laporan.stok.index', ['data' => $data]);
    }

    public function barangmasuk(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = DB::table('barang_masuk')
            ->leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.id')
            ->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
            ->leftJoin('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
            ->leftJoin('status_barang', 'barang_masuk.status_barang_id', '=', 'status_barang.id')
            ->select('barang_masuk.*', 'supplier.nama as nama_supplier', 'barang.nama as nama_barang', 'jenis_barang.nama as nama_jenis_barang', 'status_barang.nama as nama_status_barang', 'status_barang.warna as warna_status_barang')
            ->selectRaw("DATE_FORMAT(barang_masuk.tanggal, '%d %M %Y') as formatted_tanggal")
            ->when($search, function ($query) use ($search) {
                return $query->where('barang_masuk.barang_id', 'like', '%' . $search . '%')
                    ->orWhere('barang.nama', 'like', '%' . $search . '%')
                    ->orWhere('supplier.nama', 'like', '%' . $search . '%')
                    ->orWhere('status_barang.nama', 'like', '%' . $search . '%')
                    ->orWhere('barang_masuk.serial_number', 'like', '%' . $search . '%');
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('barang_masuk.tanggal', [$startDate, $endDate]);
            })
            ->orderBy('barang_masuk.tanggal', 'asc')
            ->paginate(5);

        $data->getCollection()->transform(function ($item) {
            $item->tanggal = \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY');
            return $item;
        });

        return view('laporan.barangmasuk.index', compact('data', 'startDate', 'endDate'));
    }

    public function barangkeluar(Request $request)
	{
		$search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('barang_masuk.tanggal', [$startDate, $endDate]);
        })
        ->orderBy('barang_keluar.tanggal', 'desc')
        ->orderBy('barang_masuk.serial_number', 'desc')
        ->paginate(7);

		$data->getCollection()->transform(function ($item) {
			$item->tanggal = \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD MMMM YYYY');
			return $item;
		});

        return view('laporan.barangkeluar.index', compact('data', 'startDate', 'endDate'));
	}
}