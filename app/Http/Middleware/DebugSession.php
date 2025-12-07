<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DebugSession
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Log setelah request diproses
        Log::info('=== DEBUG SESSION AFTER REQUEST ===', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'session_user_id' => session('user_id'),
            'session_all' => session()->all()
        ]);
        
        return $response;
    }
}