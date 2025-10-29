<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Display security dashboard.
     */
    public function dashboard()
    {
        return view('security.dashboard');
    }

    /**
     * Display security profile.
     */
    public function profile()
    {
        return view('security.profile');
    }
}