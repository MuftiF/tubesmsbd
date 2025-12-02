<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Events\NewAnnouncementEvent;

class AnnouncementController extends Controller
{
    // =================== ADMIN ===================
    public function indexAdmin()
    {
        $announcements = Announcement::latest()->get();
        return view('pengumuman.admin', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        $announcement = Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        // Event broadcast jika ada
        event(new NewAnnouncementEvent($announcement));

        return back()->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    // =================== USER ===================
    public function showToUsers()
    {
        $announcements = Announcement::latest()->get();
        return view('pengumuman.user', compact('announcements'));
    }

    // =================== MANAGER ===================
    // Halaman manajer melihat + buat pengumuman
public function indexManager()
{
    $announcements = Announcement::latest()->get();
    return view('pengumuman.manager', compact('announcements'));
}

// Simpan pengumuman dari manager
public function storeManager(Request $request)
{
    $request->validate([
        'judul' => 'required',
        'isi' => 'required',
    ]);

    Announcement::create([
        'judul' => $request->judul,
        'isi' => $request->isi,
    ]);

    return back()->with('success', 'Pengumuman berhasil ditambahkan!');
}

// Hapus pengumuman oleh manager
public function destroyManager($id)
{
    $announcement = Announcement::findOrFail($id);
    $announcement->delete();

    return back()->with('success', 'Pengumuman berhasil dihapus.');
}
}
