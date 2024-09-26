<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Keperluan;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class KeperluanController extends Controller
{

	public function index(Request $request)
	{
        return view('keperluan.index');
	}

	public function create()
	{
		return view('keperluan.create');
	}

	public function store(Request $request)
	{
		$response = Http::withToken(session('token'))->post(config('app.api_url') . '/keperluan', $request->all());

		if ($response->successful()) {
			return redirect('/keperluan')->with('success', 'Data berhasil ditambahkan!');
		}

		return back()->withErrors('Gagal menambahkan data keperluan.');
	}

	public function edit($id)
	{
		$response = Http::withToken(session('token'))->get(config('app.api_url') . '/keperluan/' . $id);

		if ($response->successful()) {
			$data = $response->json();
			$data = (object) $data;
			return view('keperluan.edit', compact('data'));
		}

		return redirect('/keperluan')->withErrors('Gagal mengambil data keperluan.');
	}

	public function update($id, Request $request)
	{
		$response = Http::withToken(session('token'))->put(config('app.api_url') . '/keperluan/' . $id, $request->all());

		if ($response->successful()) {
			return redirect('/keperluan')->with('success', 'Data berhasil diperbarui!');
		}

		return back()->withErrors('Gagal memperbarui data keperluan.');
	}

	public function delete($id)
	{
		$response = Http::withToken(session('token'))->delete(config('app.api_url') . '/keperluan/' . $id);

		if ($response->successful()) {
			return redirect('/keperluan')->with('success', 'Data berhasil dihapus!');
		}

		return back()->withErrors('Gagal menghapus data keperluan.');
	}

	public function deleteSelected(Request $request)
	{
		$response = Http::withToken(session('token'))->post(config('app.api_url') . '/keperluan/delete-selected', [
            'ids' => $request->input('ids')
        ]);

		if ($response->successful()) {
			return redirect('/keperluan')->with('success', 'Data terpilih berhasil dihapus!');
		}

		return back()->withErrors('Gagal menghapus data keperluan terpilih.');
	}
}
