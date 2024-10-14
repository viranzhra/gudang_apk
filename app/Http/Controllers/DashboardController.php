<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data dari API
        $response = Http::get(config('app.api_url') . '/dashboard');
        $data = $response->json();

        // Mengirim data ke Blade view
        return view('dashboard', [
            'dates' => $data['dates'],
            'months' => $data['months'],
            'counts_barang' => $data['counts_barang'],
            'counts_barang_masuk' => $data['counts_barang_masuk'],
            'counts_barang_keluar' => $data['counts_barang_keluar'],
            'counts_permintaan' => $data['counts_permintaan'],

            'total_barang' => $data['total_barang'],
            'total_barang_masuk' => $data['total_barang_masuk'],
            'total_barang_keluar' => $data['total_barang_keluar'],
            'total_permintaan' => $data['total_permintaan'],

            'counts_barang_masuk_6months' => $data['counts_barang_masuk_6months'],
            'counts_barang_keluar_6months' => $data['counts_barang_keluar_6months'],
        ]);
    }
}
