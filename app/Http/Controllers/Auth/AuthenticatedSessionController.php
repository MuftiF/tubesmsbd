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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
        // Cari user dengan no_hp ATAU name
        $user = \App\Models\User::where(function($query) use ($request) {
            $query->where('no_hp', $request->no_hp)
                ->orWhere('name', $request->no_hp);
        })->first();
        
        if ($user && \Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('admin/dashboard');
            } // ... dan seterusnya
        }
        
        return back()->withErrors(['no_hp' => 'Nomor HP/Nama atau password salah.']);
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