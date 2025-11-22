<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            'security' => redirect()->route('security.dashboard'),
            'cleaning' => redirect()->route('cleaning.dashboard'),
            'kantoran' => redirect()->route('kantoran.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }

    public function adminDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();

        $totalPegawai = User::count();
        $hadirHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->count();

        $produksiHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('palm_weight')
            ->sum('palm_weight') ?? 0;

        $rateKehadiran = $totalPegawai > 0 ? round(($hadirHariIni / $totalPegawai) * 100) : 0;

        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get();

        $roles = ['user' => 'Kebun & Panen', 'security' => 'Security', 'cleaning' => 'Cleaning', 'kantoran' => 'Administrasi'];
        $departments = [];

        foreach ($roles as $role => $name) {
            $total = User::where('role', $role)->count();
            $hadir = Attendance::whereDate('date', $today->toDateString())
                ->whereNotNull('check_in')
                ->whereHas('user', fn($q) => $q->where('role', $role))
                ->count();
            $departments[$role] = [
                'name' => $name,
                'total' => $total,
                'hadir' => $hadir,
                'percentage' => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
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
        $today = now('Asia/Jakarta')->startOfDay();

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();

        return view('user.dashboard', compact('absenHariIni'));
    }

    public function managerDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();

        $totalTim = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
        $hadirHariIni = Attendance::whereDate('date', $today->toDateString())->count();
        $produksiHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('palm_weight')
            ->sum('palm_weight') ?? 0;

        $totalTerlambat = Attendance::whereDate('date', $today->toDateString())
            ->where('status', 'terlambat')
            ->count();

        $pegawaiIdsWithAttendance = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->pluck('user_id')
            ->toArray();

        $totalAlpha = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
            ->whereNotIn('id', $pegawaiIdsWithAttendance)
            ->count();

        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get();

        return view('manager.dashboard', compact(
            'absenHariIni',
            'totalTim',
            'hadirHariIni',
            'produksiHariIni',
            'totalTerlambat',
            'totalAlpha',
            'recentActivities'
        ));
    }

    public function securityDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();
        return view('security.dashboard', compact('absenHariIni'));
    }

    public function cleaningDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();
        return view('cleaning.dashboard', compact('absenHariIni'));
    }

    public function kantoranDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();
        return view('kantoran.dashboard', compact('absenHariIni'));
    }

    public function kelolaPegawai()
    {
        $pegawai = User::all();
        return view('admin.pegawai', compact('pegawai'));
    }

    public function managerPegawai()
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
            ->orderBy('name')
            ->get();

        return view('manager.pegawai', compact('pegawai'));
    }

    public function managerTambahPegawai(Request $request)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,security,cleaning,kantoran',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function managerUpdatePegawai(Request $request, $id)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,security,cleaning,kantoran',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('manager.pegawai')->with('success', 'Data pegawai berhasil diupdate!');
    }

    public function managerHapusPegawai($id)
    {
        if (Auth::user()->role != 'manager') return redirect('/');

        $pegawai = User::findOrFail($id);

        if (Attendance::where('user_id', $id)->exists()) {
            return redirect()->route('manager.pegawai')->with('error', 'Tidak dapat menghapus pegawai yang sudah memiliki riwayat absensi!');
        }

        $pegawai->delete();

        return redirect()->route('manager.pegawai')->with('success', 'Pegawai berhasil dihapus!');
    }

public function managerLog(Request $request)
{
    // Validasi role
    if (Auth::user()->role !== 'manager') {
        return redirect('/');
    }

    $today = now('Asia/Jakarta')->startOfDay();
    $selectedDate = $request->date
        ? Carbon::parse($request->date, 'Asia/Jakarta')
        : $today;

    // Query absensi
    $query = Attendance::with('user')->whereDate('date', $selectedDate->toDateString());

    // Filter role
    if ($request->filled('role')) {
        $query->whereHas('user', fn($q) => $q->where('role', $request->role));
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter nama/email
    if ($request->filled('search')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    $attendances = $query->orderBy('check_in', 'desc')
        ->paginate(10)
        ->appends($request->except('page'));

    // Statistik
    $totalPegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
    $totalHadir = Attendance::whereDate('date', $selectedDate->toDateString())->whereNotNull('check_in')->count();
    $totalTerlambat = Attendance::whereDate('date', $selectedDate->toDateString())->where('status', 'terlambat')->count();

    $pegawaiIdsWithAttendance = Attendance::whereDate('date', $selectedDate->toDateString())
        ->whereNotNull('check_in')
        ->pluck('user_id')
        ->toArray();

    $totalAlpha = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])
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

public function laporanManager(Request $request)
{
    if (Auth::user()->role !== 'manager') {
        return redirect('/');
    }

    $today = now('Asia/Jakarta')->startOfDay();
    $startDate = $request->start_date ? Carbon::parse($request->start_date) : $today;
    $endDate = $request->end_date ? Carbon::parse($request->end_date) : $today;

    // Query dasar
    $attendancesQuery = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);

    // Filter by role
    if ($request->filled('role')) {
        $attendancesQuery->whereHas('user', fn($q) => $q->where('role', $request->role));
    }

    // Statistik
    $totalPegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();

    $totalHadir = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->distinct('user_id')
        ->count('user_id');

    $totalPalmWeight = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->sum('palm_weight') ?? 0;

    $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

    // Data chart kehadiran (7 hari terakhir)
    $chartStartDate = Carbon::parse($endDate)->subDays(6);
    $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
        ->whereBetween('date', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Data chart produksi
    $dailyPalmWeight = Attendance::selectRaw('DATE(date) as date, SUM(palm_weight) as total_weight')
        ->whereBetween('date', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Produksi per role
    $palmWeightByRole = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->get()
        ->groupBy('user.role')
        ->map(fn($items) => [
            'total_weight' => $items->sum('palm_weight'),
            'total_workers' => $items->unique('user_id')->count(),
            'avg_weight' => $items->unique('user_id')->count() > 0
                ? $items->sum('palm_weight') / $items->unique('user_id')->count()
                : 0,
        ]);

    // Top pekerja
    $topPerformers = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->selectRaw('user_id, COUNT(*) as total_hadir, SUM(palm_weight) as total_weight')
        ->groupBy('user_id')
        ->orderByDesc('total_weight')
        ->limit(5)
        ->get();

    // Data tabel detail
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

    
}
