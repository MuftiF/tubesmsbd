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
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        // Untuk development, gunakan broadcast() bukan event() untuk hindari queue
        broadcast(new NewAnnouncementEvent($announcement))->toOthers();

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
    public function indexManager()
    {
        $announcements = Announcement::latest()->get();
        return view('pengumuman.manager', compact('announcements'));
    }

    public function storeManager(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        // Broadcast juga untuk manager
        broadcast(new NewAnnouncementEvent($announcement))->toOthers();

        return back()->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function destroyManager($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}