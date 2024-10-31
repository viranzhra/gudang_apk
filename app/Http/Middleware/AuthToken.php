<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role  Role yang diperlukan untuk mengakses route ini
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null) // $role = null, sebelum permission 
    {
        // Cek Token
        if (!$request->session()->has('auth_token')) {
            return redirect()->route('login')->withErrors(['error' => 'Anda harus login terlebih dahulu.']);
        }

        $token = $request->session()->get('auth_token');

        // GET User Data
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(env('API_URL') . '/user');

        if ($response->failed()) {
            session()->forget(['auth_token', 'user_name', 'permissions']);
            return redirect()->route('login')->withErrors(['error' => 'Sesi telah berakhir. Silakan login kembali.']);        
        }

        $userData = $response->json();
        session(['user_name' => $userData['name']]);

        if (isset($userData['permissions'])) {
            session(['permissions' => $userData['permissions']]);
        }

        // if ($role && !in_array($role, $userData['roles'])) {
        //     abort(403, 'Anda tidak bisa mengakses halaman ini.');
        // }

        if ($permission && !in_array($permission, $userData['permissions'])) {
            abort(403, 'Anda tidak memiliki izin.');
        }

        return $next($request); 
    }
}
