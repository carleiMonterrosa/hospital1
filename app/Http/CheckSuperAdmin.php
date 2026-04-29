<?php
// app/Http/Middleware/CheckSuperAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('super_admin_logged')) {
            return redirect()->route('login')->with('error', 'Acceso denegado. Solo Super Administradores.');
        }
        
        return $next($request);
    }
}