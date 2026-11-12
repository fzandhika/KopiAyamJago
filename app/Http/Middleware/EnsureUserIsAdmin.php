<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Tolak akses panel admin untuk pengguna yang bukan admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_admin === true, 403, 'Halaman ini khusus admin Kopi Ayam Jago.');

        return $next($request);
    }
}
