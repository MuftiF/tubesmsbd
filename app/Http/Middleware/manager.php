<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Manager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // DEBUG LOG
        Log::info('=== MANAGER MIDDLEWARE CHECK ===', [
            'url' => $request->fullUrl(),
            'auth_check' => Auth::check(),
            'user_role' => Auth::check() ? Auth::user()->role : 'not logged in',
            'session_id' => session()->getId(),
            'all_session' => session()->all()
        ]);
        
        if (!Auth::check()) {
            Log::warning('Manager Middleware: User not authenticated');
            return redirect()->route('login');
        }
        
        if (Auth::user()->role !== 'manager') {
            Log::warning('Manager Middleware: User role mismatch', [
                'expected' => 'manager',
                'actual' => Auth::user()->role
            ]);
            return redirect('/');
        }

        Log::info('Manager Middleware: Access granted');
        return $next($request);
    }
}