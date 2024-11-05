<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $response = Http::withToken(session('jwt_token'))
            ->put(env('API_URL') . '/user/update', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if ($response->successful()) {
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return back()->withErrors($response->json());
        }
    }
}
