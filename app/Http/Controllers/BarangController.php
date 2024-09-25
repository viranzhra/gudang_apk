<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BarangController extends Controller
{

	public function index(Request $request)
	{
        return view('barang.index');
	}

    // public function create()
	// {
	// 	$response = Http::withToken(session('token'))->get(env('API_URL') . '/barang/create');

	// 	if ($response->successful()) {
	// 		$data = $response->json();
	// 		$jenis_barang = collect($data['jenis_barang'])->map(function ($item) {
	// 			return (object) $item;
	// 		});
	// 		$supplier = collect($data['supplier'])->map(function ($item) {
	// 			return (object) $item;
	// 		});
	// 		return view('barang.create', compact('jenis_barang', 'supplier'));
	// 	}
	// 	return redirect('/barang')->withErrors('Gagal mengambil data untuk membuat barang baru.');
	// }

	public function store(Request $request): RedirectResponse
	{
		$response = Http::withToken(session('token'))->post(env('API_URL') . '/barang', $request->all());

        if ($response->successful()) {
            return redirect('/barang')->with('success', 'Data berhasil ditambahkan!');
        }

        return back()->withErrors('Gagal menambahkan data barang.');
	}

	// public function edit($id)
	// {
	// 	$response = Http::withToken(session('token'))->get(env('API_URL') . '/barang/' . $id);

    //     if ($response->successful()) {
    //         $data = $response->json();
    //         $jenis_barang = collect($data['jenis_barang'])->map(function ($item) {
    //             return (object) $item;
    //         });
    //         $supplier = collect($data['supplier'])->map(function ($item) {
    //             return (object) $item;
    //         });
    //         $data = (object) $data['data'];
    //         return view('barang.edit', compact('data', 'jenis_barang', 'supplier'));
    //     }
    //     return redirect('/barang')->withErrors('Gagal mengambil data barang.');
	// }

	public function update($id, Request $request): RedirectResponse
	{
		$response = Http::withToken(session('token'))->put(env('API_URL') . '/barang/' . $id, $request->all());

        if ($response->successful()) {
            return redirect('/barang')->with('success', 'Data berhasil diperbarui!');
        }

        return back()->withErrors('Gagal memperbarui data barang.');
	}

	public function delete($id)
	{
		$response = Http::withToken(session('token'))->delete(env('API_URL') . '/barang/' . $id);

        if ($response->successful()) {
            return redirect('/barang')->with('success', 'Data berhasil dihapus!');
        }

        return back()->withErrors('Gagal menghapus data barang.');
	}

	public function deleteSelected(Request $request)
	{
		$response = Http::withToken(session('token'))->post(env('API_URL') . '/barang/delete-selected', [
            'ids' => $request->input('ids')
        ]);

        if ($response->successful()) {
            return redirect('/barang')->with('success', 'Data terpilih berhasil dihapus!');
        }

        return back()->withErrors('Gagal menghapus data barang terpilih.');
	}
}
