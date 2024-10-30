<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InitializePermissions
{
    public function handle(Request $request, Closure $next)
    {
        $permissions = session('permissions', []);
        
        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return in_array($permission, session('permissions', []));
            });
        }

        return $next($request); 
    }
}
