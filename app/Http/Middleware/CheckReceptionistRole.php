<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReceptionistRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna yang login memiliki peran "Resepsionis"
        if ($request->user() && $request->user()->profile?->role?->name === 'Resepsionis') {
            // Jika ya, izinkan akses ke halaman selanjutnya.
            return $next($request);
        }
        
        // Jika tidak, tolak akses dan tampilkan halaman 403 Forbidden.
        abort(403, 'THIS ACTION IS UNAUTHORIZED.');
    }
}