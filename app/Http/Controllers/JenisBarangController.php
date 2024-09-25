<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\JenisBarang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class JenisBarangController extends Controller
{

	// public function index(Request $request)
	// {
	// 	$search = $request->input('search');

    //     $data = JenisBarang::when($search, function ($query) use ($search) {
    //         return $query->where('nama', 'like', '%' . $search . '%');
    //     })->paginate(7);

    //     return view('jenisbarang.index', compact('data'));
	// }

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = JenisBarang::select('id', 'nama')->latest()->get();
			\Log::info('DataTables data:', ['data' => $data->toArray()]); // Log data yang dikembalikan
			return DataTables::of($data)
				->addIndexColumn()
				->addColumn('action', function ($row) {
					return '<button type="button" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '">Edit</button>'
						. '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Delete</button>';
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('jenisbarang.index');
	}
		
	
	public function create()
	{
		return view('jenisbarang.create');
	}

	public function store(Request $request): RedirectResponse
{
    // Validate the incoming request data
    $request->validate([
        'nama' => 'required|string|max:255',
    ], [
        'nama.required' => 'Nama jenis barang harus diisi.',
        'nama.string' => 'Nama jenis barang harus berupa teks.',
        'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
    ]);
    
    // Prepare the data to be sent to the external API
    $data = [
        'nama' => $request->input('nama'),
    ];

    // Send a POST request to the external API
    $response = Http::withoutVerifying()->post('https://doaibutiri.my.id/gudang/api/jenisbarang', $data);

    // Check if the API request was successful
    if ($response->successful()) {
        // Create a new JenisBarang record in the local database
        JenisBarang::create($data);

        return redirect('/jenisbarang')->with('success', 'Anda berhasil menambahkan data!');
    } else {
        // Handle the error response from the API
        return redirect('/jenisbarang')->with('error', 'Gagal menambahkan data. Silakan coba lagi.');
    }
}



	// public function store(Request $request): RedirectResponse
	// {
	// 	$response = Http::withoutVerifying()->post('https://doaibutiri.my.id/gudang/api/jenisbarang/' . $request->all());

	// 	$request->validate([
	// 		'nama' => 'required|string|max:255',
	// 	], [
	// 		'nama.required' => 'Nama jenis barang harus diisi.',
	// 		'nama.string' => 'Nama jenis barang harus berupa teks.',
	// 		'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
	// 	]);
		
	// 	$data = JenisBarang::create([
	// 		'nama' => $request->nama,
	// 	]);

	// 	return redirect('/jenisbarang')->with('success', 'Anda berhasil menambahkan data!');
	// }

		public function edit($id)
	{
		$data = JenisBarang::find($id);

		if (!$data) {
			return redirect('/jenisbarang')->with('error', 'Data tidak ditemukan.');
		}

		return view('jenisbarang.edit', ['data' => $data]);
	}

	// public function edit($id)
	// {
	// 	$response = Http::withoutVerifying()->get('https://doaibutiri.my.id/gudang/api/jenisbarang/' . $id);


	// 	$data = JenisBarang::find($id);
	// 	return view('jenisbarang.edit', ['data' => $data]);
	// }

		public function update($id, Request $request): RedirectResponse
	{
		$request->validate([
			'nama' => 'required|string|max:255',
		], [
			'nama.required' => 'Nama jenis barang harus diisi.',
			'nama.string' => 'Nama jenis barang harus berupa teks.',
			'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
		]);

		$data = JenisBarang::find($id);

		if (!$data) {
			return redirect('/jenisbarang')->with('error', 'Data tidak ditemukan.');
		}

		// Send a PUT request to the external API
		$response = Http::withoutVerifying()->put('https://doaibutiri.my.id/gudang/api/jenisbarang/' . $id, $request->all());

		if ($response->successful()) {
			$data->nama = $request->nama;
			$data->save();

			return redirect('/jenisbarang')->with('success', 'Anda berhasil memperbarui data!');
		} else {
			return redirect('/jenisbarang')->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
		}
	}


	// public function update($id, Request $request): RedirectResponse
	// {
	// 	$response = Http::withoutVerifying()->put('https://doaibutiri.my.id/gudang/api/jenisbarang/' . $id, $request->all());

	// 	$request->validate([
	// 		'nama' => 'required|string|max:255',
	// 	], [
	// 		'nama.required' => 'Nama jenis barang harus diisi.',
	// 		'nama.string' => 'Nama jenis barang harus berupa teks.',
	// 		'nama.max' => 'Nama jenis barang tidak boleh lebih dari 255 karakter.',
	// 	]);

	// 	$data = JenisBarang::find($id);

	// 	$data->nama = $request->nama;
	// 	$data->save();

	// 	return redirect('/jenisbarang')->with('success', 'Anda berhasil memperbarui data!');
	// }

	public function delete($id)
	{
		$response = Http::withoutVerifying()->delete('https://doaibutiri.my.id/gudang/api/jenisbarang/' . $id);

		$data = JenisBarang::find($id);

		$data->delete();
		return redirect('/jenisbarang')->with('success', 'Anda berhasil menghapus data!');
	}

	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids');
		foreach ($ids as $id) {
			$data = JenisBarang::find($id);
			if ($data) {
				$data->delete();
			}
		}
		return redirect('/jenisbarang')->with('success', 'Anda berhasil menghapus data terpilih!');
	}	
}
