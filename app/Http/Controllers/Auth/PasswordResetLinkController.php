<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi menggunakan no_hp
        $request->validate([
            'no_hp' => ['required', 'string'],
        ]);

        // Update ini untuk mencari user berdasarkan no_hp
        // Anda perlu membuat custom Password Broker atau override method
        
        $status = Password::sendResetLink(
            $request->only('no_hp')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('no_hp'))
                        ->withErrors(['no_hp' => __($status)]);
    }
}