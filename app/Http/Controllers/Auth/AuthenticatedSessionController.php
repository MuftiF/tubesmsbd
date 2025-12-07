<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // app/Http\Controllers\Auth\AuthenticatedSessionController.php
public function store(LoginRequest $request): RedirectResponse
{
    \Log::info('=== LOGIN PROCESS START ===');
    
    try {
        // 1. Authenticate dengan attempt manual
        $credentials = $request->only('no_hp', 'password');
        $remember = $request->boolean('remember');
        
        \Log::info('Attempting login with:', [
            'no_hp' => $credentials['no_hp'],
            'remember' => $remember
        ]);
        
        // MANUAL ATTEMPT - LEBIH KONTROL
        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'no_hp' => trans('auth.failed'),
            ]);
        }
        
        \Log::info('Auth::attempt SUCCESS');
        
        // 2. DAPATKAN USER DAN LOGIN MANUAL
        $user = Auth::user();
        \Log::info('User retrieved:', $user->only(['id', 'name', 'role']));
        
        // 3. LOGIN ULANG UNTUK MEMASTIKAN
        Auth::login($user, $remember);
        \Log::info('Auth::login() called');
        
        // 4. REGENERATE SESSION DENGAN CARA YANG BENAR
        $oldSessionId = session()->getId();
        $request->session()->regenerate();
        $newSessionId = session()->getId();
        
        \Log::info('Session regenerated:', [
            'old_id' => $oldSessionId,
            'new_id' => $newSessionId
        ]);
        
        // 5. SIMPAN DATA USER DI SESSION SECARA MANUAL
        session()->put('user_id', $user->id);
        session()->put('user_name', $user->name);
        session()->put('user_role', $user->role);
        
        // 6. FORCE SAVE SESSION
        session()->save();
        \Log::info('Session saved manually');
        
        // 7. VERIFIKASI
        \Log::info('Final auth check:', [
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'session_user_id' => session('user_id')
        ]);
        
        // 8. REDIRECT
        $redirectUrl = match($user->role) {
            'admin' => '/admin/dashboard',
            'manager' => '/manager/dashboard',
            'security' => '/security/dashboard',
            'cleaning' => '/cleaning/dashboard',
            'kantoran' => '/kantoran/dashboard',
            'user' => '/user/dashboard',
            default => '/home',
        };
        
        \Log::info('Redirecting to: ' . $redirectUrl);
        
        // GUNAKAN REDIRECT LANGSUNG, BUKAN ROUTE HELPER
        return redirect($redirectUrl);
        
    } catch (\Exception $e) {
        \Log::error('Login error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withErrors(['no_hp' => 'Login gagal: ' . $e->getMessage()]);
    }
}
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}