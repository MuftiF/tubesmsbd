<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        // Redirect berdasarkan role
        $user = Auth::user();

        switch($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'user':
                return redirect()->route('user.dashboard');
            case 'security':
                return redirect()->route('security.dashboard');
            case 'cleaning':
                return redirect()->route('cleaning.dashboard');
            case 'kantoran':
                return redirect()->route('kantoran.dashboard');
            default:
                // Fallback ke user dashboard
                return redirect()->route('user.dashboard');
        }
    }

    public function adminDashboard()
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        // Total pegawai semua role
        $totalPegawai = Pegawai::count();

        // Hadir hari ini (yang sudah check_in)
        $hadirHariIni = Attendance::whereDate('date', $today)
            ->whereNotNull('check_in')
            ->count();

        // Produksi hari ini (berat sawit)
        $produksiHariIni = Attendance::whereDate('date', $today)
            ->whereNotNull('palm_weight')
            ->sum('palm_weight') ?? 0;

        // Rate kehadiran hari ini
        $rateKehadiran = $totalPegawai > 0 ? round(($hadirHariIni / $totalPegawai) * 100) : 0;

        // Recent activities for all roles
        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get();

        // Department overview
        $departments = [
            'user' => [
                'name' => 'Kebun & Panen',
                'total' => Pegawai::where('role', 'user')->count(),
                'hadir' => Attendance::whereDate('date', $today)
                    ->whereNotNull('check_in')
                    ->whereHas('user', function($q) {
                        $q->where('role', 'user');
                    })
                    ->count(),
            ],
            'security' => [
                'name' => 'Security',
                'total' => Pegawai::where('role', 'security')->count(),
                'hadir' => Attendance::whereDate('date', $today)
                    ->whereNotNull('check_in')
                    ->whereHas('user', function($q) {
                        $q->where('role', 'security');
                    })
                    ->count(),
            ],
            'cleaning' => [
                'name' => 'Cleaning',
                'total' => Pegawai::where('role', 'cleaning')->count(),
                'hadir' => Attendance::whereDate('date', $today)
                    ->whereNotNull('check_in')
                    ->whereHas('user', function($q) {
                        $q->where('role', 'cleaning');
                    })
                    ->count(),
            ],
            'kantoran' => [
                'name' => 'Administrasi',
                'total' => Pegawai::where('role', 'kantoran')->count(),
                'hadir' => Attendance::whereDate('date', $today)
                    ->whereNotNull('check_in')
                    ->whereHas('user', function($q) {
                        $q->where('role', 'kantoran');
                    })
                    ->count(),
            ],
        ];

        // Calculate percentages
        foreach ($departments as $key => $dept) {
            $departments[$key]['percentage'] = $dept['total'] > 0 ? round(($dept['hadir'] / $dept['total']) * 100) : 0;
        }

        return view('admin.dashboard', compact(
            'totalPegawai',
            'hadirHariIni',
            'produksiHariIni',
            'rateKehadiran',
            'recentActivities',
            'departments'
        ));
    }

    public function userDashboard()
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return view('user.dashboard', compact('absenHariIni'));
    }

    public function managerDashboard()
{
    $today = \Carbon\Carbon::today('Asia/Jakarta');

    $absenHariIni = Attendance::where('user_id', Auth::id())
        ->whereDate('date', $today)
        ->first();

    $totalTim = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
    $hadirHariIni = Attendance::whereDate('date', $today)->count();

    // Produksi hari ini (berat sawit)
    $produksiHariIni = Attendance::whereDate('date', $today)
        ->whereNotNull('palm_weight')
        ->sum('palm_weight') ?? 0;

    // Total terlambat hari ini
    $totalTerlambat = Attendance::whereDate('date', $today)
        ->where('status', 'terlambat')
        ->count();

    // Total alpha hari ini (pegawai yang tidak absen)
    $pegawaiIdsWithAttendance = Attendance::whereDate('date', $today)
        ->whereNotNull('check_in')
        ->pluck('user_id')
        ->toArray();

    $totalAlpha = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
        ->whereNotIn('id', $pegawaiIdsWithAttendance)
        ->count();

    // Recent activities for team
    $recentActivities = Attendance::with('user')
        ->whereDate('date', $today)
        ->whereIn('user_id', function($query) {
            $query->select('id')
                  ->from('pegawai')
                  ->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']);
        })
        ->orderBy('check_in', 'desc')
        ->limit(5)
        ->get();

    return view('manager.dashboard', compact(
        'absenHariIni', 'totalTim', 'hadirHariIni', 'produksiHariIni', 'totalTerlambat', 'totalAlpha', 'recentActivities'
    ));
}

    public function securityDashboard()
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return view('security.dashboard', compact('absenHariIni'));
    }

    public function cleaningDashboard()
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return view('cleaning.dashboard', compact('absenHariIni'));
    }

    public function kantoranDashboard()
    {
        $today = \Carbon\Carbon::today('Asia/Jakarta');

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->first();

        return view('kantoran.dashboard', compact('absenHariIni'));
    }

    public function userRiwayat()
    {
        return view('user.riwayat');
    }

    public function kelolaPegawai()
    {
        $pegawai = Pegawai::all();
        return view('admin.pegawai', compact('pegawai'));
    }

    public function laporanAdmin(Request $request)
    {
        // Default date range
        $today = \Carbon\Carbon::today('Asia/Jakarta');
        $startDate = $request->start_date ?? $today->toDateString();
        $endDate = $request->end_date ?? $today->toDateString();

        // Base query for attendances
        $attendancesQuery = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        // Filter by role if specified
        if ($request->filled('role')) {
            $attendancesQuery->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Summary Statistics
        $totalPegawai = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();

        $totalHadir = Attendance::whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('check_in')
            ->distinct('user_id')
            ->count('user_id');

        // Total berat sawit
        $totalPalmWeight = Attendance::whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->sum('palm_weight') ?? 0;

        // Rata-rata berat sawit per pekerja
        $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

        // Daily attendance data for chart (last 7 days from end date)
        $chartStartDate = \Carbon\Carbon::parse($endDate)->subDays(6);
        $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
            ->whereBetween('date', [$chartStartDate, $endDate])
            ->whereNotNull('check_in')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily palm weight data for chart (last 7 days from end date)
        $dailyPalmWeight = Attendance::selectRaw('DATE(date) as date, SUM(palm_weight) as total_weight')
            ->whereBetween('date', [$chartStartDate, $endDate])
            ->whereNotNull('palm_weight')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Palm weight by role
        $palmWeightByRole = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->get()
            ->groupBy('user.role')
            ->map(function($items) {
                return [
                    'total_weight' => $items->sum('palm_weight'),
                    'total_workers' => $items->unique('user_id')->count(),
                    'avg_weight' => $items->unique('user_id')->count() > 0
                        ? $items->sum('palm_weight') / $items->unique('user_id')->count()
                        : 0,
                ];
            });

        // Top performers by palm weight
        $topPerformers = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->selectRaw('user_id, COUNT(*) as total_hadir, SUM(palm_weight) as total_weight')
            ->groupBy('user_id')
            ->orderByDesc('total_weight')
            ->limit(5)
            ->get();

        // Detailed attendance records
        $detailedAttendances = $attendancesQuery
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'asc')
            ->paginate(10)
            ->appends($request->except('page'));

        return view('admin.laporan', compact(
            'startDate',
            'endDate',
            'totalPegawai',
            'totalHadir',
            'totalPalmWeight',
            'averagePalmWeight',
            'dailyAttendance',
            'dailyPalmWeight',
            'palmWeightByRole',
            'topPerformers',
            'detailedAttendances'
        ));
    }

    public function laporanManager(Request $request)
    {
        // Default date range
        $today = \Carbon\Carbon::today('Asia/Jakarta');
        $startDate = $request->start_date ?? $today->toDateString();
        $endDate = $request->end_date ?? $today->toDateString();

        // Base query for attendances
        $attendancesQuery = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        // Filter by role if specified
        if ($request->filled('role')) {
            $attendancesQuery->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Summary Statistics
        $totalPegawai = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();

        $totalHadir = Attendance::whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('check_in')
            ->distinct('user_id')
            ->count('user_id');

        // Total berat sawit
        $totalPalmWeight = Attendance::whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->sum('palm_weight') ?? 0;

        // Rata-rata berat sawit per pekerja
        $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

        // Daily attendance data for chart (last 7 days from end date)
        $chartStartDate = \Carbon\Carbon::parse($endDate)->subDays(6);
        $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
            ->whereBetween('date', [$chartStartDate, $endDate])
            ->whereNotNull('check_in')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily palm weight data for chart (last 7 days from end date)
        $dailyPalmWeight = Attendance::selectRaw('DATE(date) as date, SUM(palm_weight) as total_weight')
            ->whereBetween('date', [$chartStartDate, $endDate])
            ->whereNotNull('palm_weight')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Palm weight by role
        $palmWeightByRole = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->get()
            ->groupBy('user.role')
            ->map(function($items) {
                return [
                    'total_weight' => $items->sum('palm_weight'),
                    'total_workers' => $items->unique('user_id')->count(),
                    'avg_weight' => $items->unique('user_id')->count() > 0
                        ? $items->sum('palm_weight') / $items->unique('user_id')->count()
                        : 0,
                ];
            });

        // Top performers by palm weight
        $topPerformers = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('palm_weight')
            ->selectRaw('user_id, COUNT(*) as total_hadir, SUM(palm_weight) as total_weight')
            ->groupBy('user_id')
            ->orderByDesc('total_weight')
            ->limit(5)
            ->get();

        // Detailed attendance records
        $detailedAttendances = $attendancesQuery
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'asc')
            ->paginate(10)
            ->appends($request->except('page'));

        return view('manager.laporan', compact(
            'startDate',
            'endDate',
            'totalPegawai',
            'totalHadir',
            'totalPalmWeight',
            'averagePalmWeight',
            'dailyAttendance',
            'dailyPalmWeight',
            'palmWeightByRole',
            'topPerformers',
            'detailedAttendances'
        ));
    }

    public function userLog()
{
    // Validasi role
    if (Auth::user()->role != 'user') {
        return redirect('/');
    }

    return view('user.riwayat');
}

public function managerLog(Request $request)
{
    // Validasi role
    if (Auth::user()->role != 'manager') {
        return redirect('/');
    }

    // Default date is today
    $selectedDate = $request->date ? \Carbon\Carbon::parse($request->date, 'Asia/Jakarta') : \Carbon\Carbon::today('Asia/Jakarta');

    // Query untuk absensi dengan filter
    $query = Attendance::with('user')
        ->whereDate('date', $selectedDate);

    // Filter by role
    if ($request->filled('role')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('role', $request->role);
        });
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by search (nama pegawai)
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    $attendances = $query->orderBy('check_in', 'desc')
                        ->paginate(10)
                        ->appends($request->except('page'));

    // Statistics
    $totalPegawai = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();

    // Total hadir (yang sudah check_in)
    $totalHadir = Attendance::whereDate('date', $selectedDate)
        ->whereNotNull('check_in')
        ->count();

    // Total terlambat
    $totalTerlambat = Attendance::whereDate('date', $selectedDate)
        ->where('status', 'terlambat')
        ->count();

    // Total alpha (pegawai yang tidak absen)
    $pegawaiIdsWithAttendance = Attendance::whereDate('date', $selectedDate)
        ->whereNotNull('check_in')
        ->pluck('user_id')
        ->toArray();

    $totalAlpha = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
        ->whereNotIn('id', $pegawaiIdsWithAttendance)
        ->count();

    return view('manager.log', compact(
        'attendances',
        'totalPegawai',
        'totalHadir',
        'totalTerlambat',
        'totalAlpha',
        'selectedDate'
    ));
}

    /**
     * Halaman kelola pegawai untuk manager
     */
    public function managerPegawai()
    {
        // Validasi role
        if (Auth::user()->role != 'manager') {
            return redirect('/');
        }

        $pegawai = Pegawai::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
            ->orderBy('name')
            ->get();

        return view('manager.pegawai', compact('pegawai'));
    }

    /**
     * Tambah pegawai baru
     */
    public function managerTambahPegawai(Request $request)
    {
        if (Auth::user()->role != 'manager') {
            return redirect('/');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email',
            'role' => 'required|in:user,security,cleaning,kantoran',
            'password' => 'required|min:6',
        ]);

        Pegawai::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    /**
     * Update data pegawai
     */
    public function managerUpdatePegawai(Request $request, $id)
    {
        if (Auth::user()->role != 'manager') {
            return redirect('/');
        }

        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email,' . $id,
            'role' => 'required|in:user,security,cleaning,kantoran',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('manager.pegawai')->with('success', 'Data pegawai berhasil diupdate!');
    }

    /**
     * Hapus pegawai
     */
    public function managerHapusPegawai($id)
    {
        if (Auth::user()->role != 'manager') {
            return redirect('/');
        }

        $pegawai = Pegawai::findOrFail($id);

        // Cek apakah pegawai memiliki data absensi
        $hasAttendance = Attendance::where('user_id', $id)->exists();

        if ($hasAttendance) {
            return redirect()->route('manager.pegawai')->with('error', 'Tidak dapat menghapus pegawai yang sudah memiliki riwayat absensi!');
        }

        $pegawai->delete();

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil dihapus!');
    }
}

