<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\PermintaanBarangKeluar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;

class PermintaanBarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        return view('permintaanbarangkeluar.index');
    }

    public function create($id = null)
    {
        try {
            $response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/create');

            if ($response->successful()) {
                $data = $response->json();
                $jenis_barang = collect($data['jenis_barang'])->map(function ($item) {
                    return (object) $item;
                });
                $barang = collect($data['barang'])->map(function ($item) {
                    return (object) $item;
                });

                $customer = isset($data['customer']) ? collect($data['customer'])->map(function ($item) {
                    return (object) $item;
                }) : null;

                // if (App::make('permission')->canAny(['item request.create', 'item request.viewFilterbyUser'])) {
                //     $customer = (object) $data['customer'];
                // }

                $keperluan = collect($data['keperluan'])->map(function ($item) {
                    return (object) $item;
                });

                $barangMasuk = null;
                $jenis_barang_id = null;
                $barangbyjenis = null;

                if ($id !== null) {
                    $barangMasukResponse = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/create/' . $id);
                    if ($barangMasukResponse->successful()) {
                        $barangMasukData = $barangMasukResponse->json();
                        $barangMasuk = isset($barangMasukData['barangMasuk']) ? (object) $barangMasukData['barangMasuk'] : null;
                        $jenis_barang_id = $barangMasukData['jenis_barang_id'] ?? null;
                        $barangbyjenis = isset($barangMasukData['barangbyjenis'])
                            ? collect($barangMasukData['barangbyjenis'])->map(function ($item) {
                                return (object) $item;
                            })
                            : collect();
                    }
                }

                return view('permintaanbarangkeluar.create', compact('barangMasuk', 'customer', 'barang', 'barangbyjenis', 'jenis_barang', 'jenis_barang_id', 'keperluan'));
            }

            return redirect('/permintaanbarangkeluar')->with('error', 'Gagal mengambil data untuk membuat permintaan barang keluar baru.');
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Could not resolve host: doaibutiri.my.id') !== false) {
                return redirect('/permintaanbarangkeluar')->with('error', 'Tidak dapat terhubung ke server. Silakan periksa koneksi internet Anda dan coba lagi nanti.');
            }
            return redirect('/permintaanbarangkeluar')->with('error', 'Terjadi kesalahan dengan server. Silakan coba lagi nanti.');
        }
    }

    public function getBarangByJenis($id)
    {
        // $barang = DB::table('barang')->where('jenis_barang_id', $id)->orderBy('nama', 'asc')->get();
        // return response()->json($barang);
		$response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/get-by-jenis/' . $id);
		
		if ($response->successful()) {
			$barang = $response->json();
			return response()->json($barang);
		}
		
		return response()->json(['error' => 'Failed to fetch data'], 500);
    }

    public function getStok($id)
	{
		$response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/get-stok/' . $id);

		if ($response->successful()) {
			$stok = $response->json();
			return response()->json($stok);
		}

		return response()->json(['error' => 'Failed to fetch stock data'], 500);
	}

    public function getSerialNumberByBarang($id)
    {
		$response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/get-by-barang/' . $id);
		
		if ($response->successful()) {
			$serialnumber = $response->json();
			return response()->json($serialnumber);
		}
		
		return response()->json(['error' => 'Failed to fetch data'], 500);

        // $serialnumber = DB::table('serial_number')
        //     ->join('barang_masuk', 'serial_number.barangmasuk_id', '=', 'barang_masuk.id')
        //     ->where('barang_masuk.barang_id', $id)
        //     /*->whereNotExists(function ($query) {
		// 		$query->select(DB::raw(1))
		// 		->from('permintaan_barang_keluar')
		// 		->whereRaw('permintaan_barang_keluar.barangmasuk_id = serial_number.id')
		// 		->where(function ($subQuery) {
		// 		$subQuery->whereNull('permintaan_barang_keluar.status')
		// 		->orWhere('permintaan_barang_keluar.status', '!=', 'Ditolak');
		// 		});
		// 	})*/
        //     ->orderBy('serial_number.serial_number', 'asc') // Mengurutkan berdasarkan serial_number
        //     ->pluck('serial_number.serial_number'); // Memilih kolom serial_number dari tabel serial_number

        // return response()->json($serialnumber);
    }

    public function store(Request $request): RedirectResponse
    {
        $response = Http::withToken(session('jwt_token'))->post(config('app.api_url') . '/permintaanbarangkeluar', $request->all());

        if ($response->successful()) {
            return redirect('/permintaanbarangkeluar')->with('success', $response->json('message'));
        }

        if ($response->status() === 422) {
            $responseData = $response->json();
            if (isset($responseData['message'])) {
                if (is_array($responseData['message'])) {
                    return back()->withErrors($responseData['message'])->withInput();
                }
            }
        }

        return redirect('/permintaanbarangkeluar')->withErrors('Gagal menambahkan data permintaan barang keluar.')->withInput();
    }

    public function delete($id)
    {
        $response = Http::withToken(session('jwt_token'))->delete(config('app.api_url') . '/permintaanbarangkeluar/' . $id);

        if ($response->successful()) {
            return redirect('/permintaanbarangkeluar')->with('success', $response->json('message'));
        }

        return back()->withErrors('Gagal menghapus data permintaan barang keluar.');
    }

    public function updateStatus(Request $request)
    {
        $response = Http::withToken(session('jwt_token'))->post(config('app.api_url') . '/permintaanbarangkeluar/update-status', $request->all());

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => $response->json()['message'],
            ]);
        }

        return response()->json(
            [
                'success' => false,
                'message' => $response->json()['message'],
            ],
            $response->status(),
        );
    }

    public function insertProjectPO(Request $request)
    {
        $response = Http::withToken(session('jwt_token'))->post(config('app.api_url') . '/permintaanbarangkeluar/insert-project-po', $request->all());

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => $response->json()['message'],
            ]);
        }

        return response()->json(
            [
                'success' => false,
                'message' => $response->json()['message'],
            ],
            $response->status(),
        );
    }

    public function selectSN($id)
    {
        $response = Http::withToken(session('jwt_token'))->get(config('app.api_url') . '/permintaanbarangkeluar/selectSN/' . $id);

        if ($response->successful()) {
            $serialNumbers = collect($response->json())->map(function ($item) {
                return (object) $item;
            });

            // Mengelompokkan SN berdasarkan barang_id
            $groupedSerialNumbers = $serialNumbers->groupBy('barang_id');

            return view('permintaanbarangkeluar.selectSN', compact('groupedSerialNumbers', 'id'));
        }

        if ($response->status() === 404) {
            // Ambil pesan dari API
            $message = $response->json('message');
            return redirect('/permintaanbarangkeluar')->with('error', $message);
        }
        
        return redirect('/permintaanbarangkeluar')->with('error', $message);
    }

    public function setSN(Request $request)
    {
        $validated = $request->validate([
            'permintaan_id' => 'required|integer',
            'serial_number_ids' => 'required|array',
            'serial_number_ids.*' => 'required|array',  // Barang ID
            'serial_number_ids.*.*' => 'required|integer',  // SN ID
        ]);

        $payload = [
            'permintaan_id' => $validated['permintaan_id'],
            'serial_number_ids' => $validated['serial_number_ids']
        ];

        // \Log::info('Mengirim data ke API:', $payload);

        $response = Http::withToken(session('jwt_token'))->post(config('app.api_url') . '/permintaanbarangkeluar/setSN', $payload);

        if ($response->successful()) {
            return redirect()->route('permintaanbarangkeluar.index')->with('success', $response->json('message'));
        }

        return redirect()->route('permintaanbarangkeluar.index')->with('error', $response->json('message'));
    }

}