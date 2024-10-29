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

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {

        return view('barangkeluar.index');
    }

    public function show()
	{
		$response = Http::withToken(session('token'))->get(config('app.api_url') . '/barangkeluar');
		
		if ($response->successful()) {
			$data = $response->json();
			return response()->json($data);
		}
		
		return response()->json(['error' => 'Failed to fetch data'], 500);
	}
}
