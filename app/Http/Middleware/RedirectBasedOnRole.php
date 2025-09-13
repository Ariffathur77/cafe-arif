<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika user adalah Kasir, arahkan ke halaman Pesanan Masuk
        if ($user && strtolower($user->role->name) === 'cashier') {
            return redirect()->route('admin.orders');
        }

        // Jika bukan, lanjutkan ke tujuan awal (misalnya Dashboard untuk Owner)
        return $next($request);
    }
}
