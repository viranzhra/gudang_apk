<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna memiliki token autentikasi dalam sesi
        if ($request->session()->has('jwt_token')) {
            // Jika ya, arahkan pengguna ke dashboard atau halaman utama
            return redirect()->route('dashboard')->with('message', 'Anda sudah login.');
        } 

        // Jika tidak ada token, lanjutkan request ke halaman tujuan (login, register, dll.)
        return $next($request);
    }
}

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;

// class RedirectIfAuthenticated
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         if ($request->session()->has('auth_token')) {
//             return redirect()->route('dashboard');
//         } 

//         return $next($request);
//     }
// }
