<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    // halaman admin lihat + buat pengumuman
    public function indexAdmin()
    {
        $announcements = Announcement::latest()->get();
        return view('pengumuman.admin', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return back()->with('success', 'Pengumuman berhasil dibuat.');
    }

    // halaman user melihat pengumuman
    public function showToUsers()
    {
        $announcements = Announcement::latest()->get();
        return view('pengumuman.user', compact('announcements'));
    }
}
