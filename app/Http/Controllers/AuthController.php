<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $response = Http::post(env('API_URL') . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $data = $response->json();
            
        if (isset($data['error'])) {
            if ($response->status() === 404) {
                return back()->withErrors(['error' => 'Email tidak ditemukan']);
            }
            if ($response->status() === 401) {
                return back()->withErrors(['error' => 'Password tidak valid']);
            }
            return back()->withErrors(['error' => $data['error']]);
        }

        if ($response->successful()) {
            session(['jwt_token' => $data['token']]);
            session(['user_name' => $data['user']['name']]);

            return redirect()->route('dashboard')->with('success', $data['message'] ?? 'Login berhasil');
        } else {
            return back()->withErrors(['error' => 'Login gagal, periksa kredensial Anda.']);
        }
    }
    
    /**
     * Menampilkan form registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        $response = Http::post(env('API_URL') . '/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
        } else {
            return back()->withErrors($response->json());
        }
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        session()->forget(['jwt_token', 'user_name', 'roles', 'permissions']);
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Http;

// class AuthController extends Controller
// {
//     public function showLoginForm()
//     {
//         return view('auth.login');
//     }

//     public function login(Request $request)
//     {
//         $response = Http::withHeaders([
//             'Accept' => 'application/json',
//         ])->post(env('API_URL') . '/login', [
//             'email' => $request->email,
//             'password' => $request->password,
//         ]);

//         if ($response->successful()) {
//             $data = $response->json();
            
//             if (isset($data['data']['token'])) {
//                 session(['auth_token' => $data['data']['token']]); // Simpan token di sesi
//                 session(['user_name' => $data['data']['name']]);   // Simpan nama

//                 if (isset($data['data']['permissions'])) {
//                     session(['permissions' => $data['data']['permissions']]); // Simpan permission
//                 } 

//                 return redirect()->route('dashboard')->with('success', 'Login berhasil');
//             } else {
//                 return back()->withErrors(['error' => 'Login gagal, token tidak ditemukan.']);
//             }
//         } else {
//             return back()->withErrors(['error' => 'Login gagal, silakan periksa kembali kredensial Anda.']);
//         }        
//     }

//     public function showRegisterForm()
//     {
//         return view('auth.register');
//     }

//     public function register(Request $request)
//     {
//         $response = Http::withHeaders([
//             'Accept' => 'application/json',
//         ])->post(env('API_URL') . '/register', [            
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => $request->password,
//             'c_password' => $request->password_confirmation,
//         ]);

//         if ($response->successful()) {
//             return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
//         } else {
//             return back()->withErrors($response->json()['data']);
//         }
//     }

//     public function logout()
//     {
//         session()->forget(['auth_token', 'user_name', 'permissions']);
//         return redirect()->route('login')->with('success', 'Logout berhasil.');
//     }
// }
