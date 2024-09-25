<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\StatusBarang;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class StatusBarangController extends Controller
{

	// public function index(Request $request)
	// {
	// 	$search = $request->input('search');

    //     $data = StatusBarang::when($search, function ($query) use ($search) {
    //         return $query->where('nama', 'like', '%' . $search . '%');
    //     })->paginate(7);

    //     return view('statusbarang.index', compact('data'));
	// }

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = StatusBarang::select('id', 'nama')->latest();
			return DataTables::of($data)
				->addIndexColumn()
				->make(true);
		}
	
		return view('statusbarang.index');
	}
	

	public function create()
	{
		return view('statusbarang.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$response = Http::post('https://doaibutiri.my.id/gudang/api/statusbarang', $request->all());

        if ($response->successful()) {
            return redirect('/statusbarang')->with('success', 'Data berhasil ditambahkan!');
        }

        return back()->withErrors('Gagal menambahkan data status barang.');
	}

	public function edit($id)
	{
		$response = Http::get('https://doaibutiri.my.id/gudang/api/statusbarang/' . $id);

        if ($response->successful()) {
            $data = $response->json();
            $data = (object) $data;
            return view('statusbarang.edit', compact('data'));
        }
        return redirect('/statusbarang')->withErrors('Gagal mengambil data status barang.');
	}

	public function update($id, Request $request): RedirectResponse
	{
		$response = Http::put('https://doaibutiri.my.id/gudang/api/statusbarang/' . $id, $request->all());

        if ($response->successful()) {
            return redirect('/statusbarang')->with('success', 'Data berhasil diperbarui!');
        }

        return back()->withErrors('Gagal memperbarui data status barang.');
	}

	public function delete($id)
	{
		$response = Http::delete('https://doaibutiri.my.id/gudang/api/statusbarang/' . $id);

        if ($response->successful()) {
            return redirect('/statusbarang')->with('success', 'Data berhasil dihapus!');
        }

        return back()->withErrors('Gagal menghapus data status barang.');
	}
	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$statusBarang = StatusBarang::find($id);
			$barangMasuk = BarangMasuk::where('status_barang_id', $id)->get();

			foreach ($barangMasuk as $item) {
				$barang = Barang::find($item->barang_id);
				if ($barang) {
					$barang->jumlah -= $item->jumlah;
					$barang->save();
				}
				$item->delete();
			}

			$statusBarang->delete();
		}
		return response()->json(['success' => 'Data berhasil dihapus']);
	}
}
