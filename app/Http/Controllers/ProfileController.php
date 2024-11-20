<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        $response = Http::withToken(session('jwt_token'))
            ->get(env('API_URL') . '/user');

        if ($response->successful()) {
            $user = $response->json();
            return view('profile.user_profile', compact('user'));
        } else {
            return redirect()->route('dashboard')->withErrors(['error' => 'Tidak dapat mengambil data pengguna.']);
        }
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $http = Http::withToken(session('jwt_token'))
                ->attach(
                    'photo',
                    file_get_contents($request->file('photo')->getPathname()),
                    $request->file('photo')->getClientOriginalName()
                )
                ->asMultipart()
                ->post(env('API_URL') . '/user/update-photo');

            if ($http->successful()) {
                return response()->json(['success' => true, 'message' => 'Foto berhasil diperbarui.']);
            }

            // Tambahkan logging error dari Laravel B
            \Log::error('Error dari API Laravel B:', $http->json());
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah foto.', 'error' => $http->json()]);
        } catch (\Exception $e) {
            \Log::error('Error di Laravel A:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghubungi API.', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Proses pembaruan profil
     */
    public function update(Request $request)
    {
        // Validasi input di Laravel A
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Persiapkan data untuk dikirim ke API
        $data = [
            '_method' => 'PUT', // Simulasikan metode PUT
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
            $data['password_confirmation'] = $request->password_confirmation;
        }

        // Inisialisasi HTTP Client dengan token
        $http = Http::withToken(session('jwt_token'));

        // Jika ada foto, lampirkan sebagai multipart
        if ($request->hasFile('profile_photo')) {
            $http = $http->attach(
                'photo',
                file_get_contents($request->file('profile_photo')->getPathname()),
                $request->file('profile_photo')->getClientOriginalName()
            );
        }

        // Kirim permintaan POST dengan data multipart
        $response = $http->asMultipart()->post(env('API_URL') . '/user/update', $data);

        if ($response->successful()) {
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
        } else {
            // Jika ada kesalahan validasi dari API
            return back()->withErrors($response->json());
        }
    }

}
