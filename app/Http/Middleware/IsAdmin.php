<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login DAN memiliki peran 'Admin'
        if (auth()->check() && auth()->user()->profile?->role?->name === 'Admin') {
            // Jika ya, izinkan akses
            return $next($request);
        }

        // Jika tidak, tendang ke halaman dashboard biasa
        abort(403, 'Unauthorized Access'); 
    }
}