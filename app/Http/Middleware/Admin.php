<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // TAMBAHKAN Auth::check() seperti middleware lainnya
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}