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
            return view('profile.edit', compact('user'));
        } else {
            return redirect()->route('dashboard')->withErrors(['error' => 'Tidak dapat mengambil data pengguna.']);
        }
    }

    /**
     * Proses pembaruan profil
     */
    public function update(Request $request)
    {
        // Validasi input di Laravel A (opsional)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        if ($request->hasFile('photo')) {
            $http = $http->attach(
                'photo',
                file_get_contents($request->file('photo')->getPathname()),
                $request->file('photo')->getClientOriginalName()
            );
        }

        // Kirim permintaan POST dengan data multipart
        $response = $http->asMultipart()->post(env('API_URL') . '/user/update', $data);

        if ($response->successful()) {
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
        } else {
            // Jika ada kesalahan validasi dari API
            return back()->withErrors($response->json());
        }
    }
}
