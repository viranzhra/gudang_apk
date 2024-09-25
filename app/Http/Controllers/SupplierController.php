<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SupplierController extends Controller
{

    // Menampilkan halaman index supplier
    public function index(Request $request)
    {
        return view('supplier.index');
    }

    // Menampilkan form create supplier
    // public function create()
    // {
    //     return view('supplier.create');
    // }

    public function store(Request $request)
    {
        // Logging request data yang akan dikirim ke API
        Log::info('Sending supplier data to API:', $request->all());

        // Mengirim request POST ke API untuk menyimpan data supplier
        $response = Http::withToken(session('token'))->post(config('app.api_url') . '/suppliers', $request->all());

        // Logging response dari API
        Log::info('API Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        // Mengecek apakah request berhasil
        if ($response->successful()) {
            return redirect('/supplier')->with('success', 'Data berhasil ditambahkan!');
        }

        // Jika gagal, kembali ke halaman sebelumnya dengan pesan error
        return back()->withErrors('Gagal menambahkan data supplier.');
    }


    // Menampilkan form edit supplier dengan data dari API
    public function edit($id)
    {
        // Mengirim request GET ke API untuk mendapatkan data supplier
        $response = Http::withToken(session('token'))->get(config('app.api_url') . '/suppliers/' . $id);

        // Mengecek apakah request berhasil
        if ($response->successful()) {
            // Mengambil data supplier dalam format JSON
            $data = $response->json();
            return view('supplier.index', compact('data'));
        }

        // Jika gagal, kembali ke halaman index dengan pesan error
        return redirect('/supplier')->withErrors($response->json('message', 'Gagal mengambil data supplier.'));
    }

    // Memperbarui data supplier melalui API
    public function update($id, Request $request)
    {
        // Mengirim request PUT ke API untuk memperbarui data supplier
        $response = Http::withToken(session('token'))->put(config('app.api_url') . '/suppliers/' . $id, $request->all());

        // Mengecek apakah request berhasil
        if ($response->successful()) {
            return redirect('/supplier')->with('success', 'Data berhasil diperbarui!');
        }

        // Jika gagal, kembali ke halaman sebelumnya dengan pesan error
        return back()->withErrors('Gagal memperbarui data supplier.');
    }

    // Menghapus data supplier melalui API
    public function delete($id)
    {
        // Mengirim request DELETE ke API untuk menghapus supplier
        $response = Http::withToken(session('token'))->delete(config('app.api_url') . '/suppliers/' . $id);

        // Mengecek apakah request berhasil
        if ($response->successful()) {
            return redirect('/supplier')->with('success', 'Data berhasil dihapus!');
        }

        // Jika gagal, kembali ke halaman sebelumnya dengan pesan error
        return back()->withErrors('Gagal menghapus data supplier.');
    }

    // Menghapus data supplier yang dipilih melalui API
    public function deleteSelected(Request $request)
    {
        // Mengirim request POST ke API untuk menghapus supplier yang dipilih
        $response = Http::withToken(session('token'))->post(config('app.api_url') . '/suppliers/delete-selected', [
            'ids' => $request->input('ids')
        ]);

        // Mengecek apakah request berhasil
        if ($response->successful()) {
            return redirect('/supplier')->with('success', 'Data terpilih berhasil dihapus!');
        }

        // Jika gagal, kembali ke halaman sebelumnya dengan pesan error
        return back()->withErrors('Gagal menghapus data supplier terpilih.');
    }
}
