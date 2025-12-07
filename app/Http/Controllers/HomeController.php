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
    \Log::warning('=== DASHBOARD ROUTE ACCESSED ===', [
        'user' => Auth::check() ? Auth::user()->only(['id', 'name', 'role']) : 'not logged in',
        'url' => request()->fullUrl()
    ]);
    
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    \Log::info('Redirecting from dashboard to role dashboard', ['role' => $user->role]);
    
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'manager' => redirect()->route('manager.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        'security' => redirect()->route('security.dashboard'),
        'cleaning' => redirect()->route('cleaning.dashboard'),
        'kantoran' => redirect()->route('kantoran.dashboard'),
        default => redirect()->route('home'), // JANGAN redirect ke dashboard
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
       $produksiHariIni = \App\Models\CatatanPanen::whereDate('tanggal', $today->toDateString())
        ->sum('berat_kg') ?? 0; // Mengambil SUM dari 'berat_kg'

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

        \Log::info('=== MANAGER DASHBOARD ACCESSED ===');
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
            'no_hp' => 'required|string|unique:users,no_hp', // UBAH: email -> no_hp
            'role' => 'required|in:user,security,cleaning,kantoran',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp, // UBAH: email -> no_hp
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
            'no_hp' => 'required|string|unique:users,no_hp,' . $id, // UBAH: email -> no_hp
            'role' => 'required|in:user,security,cleaning,kantoran',
        ]);

        $data = [
            'name' => $request->name,
            'no_hp' => $request->no_hp, // UBAH: email -> no_hp
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        return redirect()->route('manager.pegawai')->with('success', 'Data pegawai berhasil diupdate!');
    }

 public function laporanAdmin(Request $request)
{
    $today = now('Asia/Jakarta')->startOfDay();

    $startDate = $request->start_date
        ? \Carbon\Carbon::parse($request->start_date)
        : $today;

    $endDate = $request->end_date
        ? \Carbon\Carbon::parse($request->end_date)
        : $today;

    // Query utama
    $attendancesQuery = Attendance::with('user')
        ->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ]);

    // Filter role
    if ($request->filled('role')) {
        $attendancesQuery->whereHas('user', function ($q) use ($request) {
            $q->where('role', $request->role);
        });
    }

    // Statistik utama
    $totalPegawai = User::count();

    $totalPalmWeight = Attendance::whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->whereNotNull('palm_weight')
        ->sum('palm_weight') ?? 0;

    $totalHadir = Attendance::whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->whereNotNull('check_in')
        ->distinct('user_id')
        ->count('user_id');

    $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

    // Chart: Kehadiran
    $chartStartDate = \Carbon\Carbon::parse($endDate)->subDays(6);
    $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
        ->whereBetween('date', [
            $chartStartDate->toDateString(),
            $endDate->toDateString()
        ])
        ->whereNotNull('check_in')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Chart: Produksi Sawit
   $dailyPalmWeight = \App\Models\CatatanPanen::selectRaw('DATE(tanggal) as date, SUM(berat_kg) as total_weight')
        ->whereBetween('tanggal', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Produksi per Role
    $palmWeightByRole = Attendance::with('user')
        ->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->whereNotNull('palm_weight')
        ->get()
        ->groupBy('user.role')
        ->map(function ($items) {
            return [
                'total_weight' => $items->sum('palm_weight'),
                'total_workers' => $items->unique('user_id')->count(),
                'avg_weight' => $items->unique('user_id')->count() > 0
                    ? $items->sum('palm_weight') / $items->unique('user_id')->count()
                    : 0,
            ];
        });

    // Top pekerja
    $topPerformers = Attendance::with('user')
        ->whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->whereNotNull('palm_weight')
        ->selectRaw('user_id, COUNT(*) as total_hadir, SUM(palm_weight) as total_weight')
        ->groupBy('user_id')
        ->orderByDesc('total_weight')
        ->limit(5)
        ->get();

    // Detail tabel
    $detailedAttendances = $attendancesQuery
        ->orderBy('date', 'desc')
        ->orderBy('check_in', 'asc')
        ->paginate(10)
        ->appends($request->except('page'));

    return view('admin.laporan', compact(
        'startDate',
        'endDate',
        'totalPegawai',
        'totalPalmWeight',
        'averagePalmWeight',
        'totalHadir',
        'dailyAttendance',
        'dailyPalmWeight',
        'palmWeightByRole',
        'topPerformers',
        'detailedAttendances'
    ));
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
    // PERBAIKAN: Filter keras role 'user' pada total hadir dan terlambat jika diinginkan
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

    // Query dasar untuk detail tabel (Filter Keras: 'user')
   $attendancesQuery = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereHas('user', fn($q) => $q->where('role', 'user'));
        
    // Filter by role opsional dari UI
    if ($request->filled('role')) {
        $attendancesQuery->whereHas('user', fn($q) => $q->where('role', $request->role));
    }

    // Statistik

    // 1. Total Pegawai (Hanya role 'user')
    $totalPegawai = User::whereIn('role', ['user'])->count(); // OK

    // 2. Total Hadir (Hanya role 'user')
    $totalHadir = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->whereHas('user', fn($q) => $q->where('role', 'user')) // OK
        ->distinct('user_id')
        ->count('user_id');

    // 3. Total Berat Sawit (Calculation via Attendance model - TIDAK DIFILTER)
    // BLOK INI HARUS DIHAPUS JIKA CatatanPanen ADALAH SUMBER DATA RESMI
    // $totalPalmWeight = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
    //     ->whereNotNull('palm_weight')
    //     ->sum('palm_weight') ?? 0;

    // Data chart kehadiran (7 hari terakhir, difilter ke 'user')
    $chartStartDate = Carbon::parse($endDate)->subDays(6);
    $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
        ->whereBetween('date', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->whereHas('user', fn($q) => $q->where('role', 'user')) // PERBAIKAN: Filter Ditambahkan
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Data chart produksi (Masih menggunakan Attendance dan TIDAK DIFILTER)
    // BLOK INI HARUS DIGANTI MENGGUNAKAN CatatanPanen DAN DIFILTER
    // $dailyPalmWeight = Attendance::selectRaw('DATE(date) as date, SUM(palm_weight) as total_weight')
    //     ->whereBetween('date', [$chartStartDate->toDateString(), $endDate->toDateString()])
    //     ->whereNotNull('palm_weight')
    //     ->groupBy('date')
    //     ->orderBy('date')
    //     ->get();


    // --- SUMBER DATA RESMI CatatanPanen DARI SINI ---

    // 4. Query Catatan Panen untuk Total Berat (Memperbaiki Relationship dan Redundansi)
    $palmWeightQuery = \App\Models\CatatanPanen::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);

    // FILTER KERAS: Hanya ambil catatan panen dari role 'user'
    $palmWeightQuery->whereHas('pegawai', fn($q) => $q->where('role', 'user')); // <-- PERBAIKAN: Gunakan 'pegawai'

    // Filter role opsional dari UI (jika ada)
    if ($request->filled('role')) {
        $palmWeightQuery->whereHas('pegawai', fn($q) => $q->where('role', $request->role)); // <-- OK
    }

    $totalPalmWeight = $palmWeightQuery->sum('berat_kg') ?? 0; // <-- Hapus Redundansi dan Bug

    // 5. Rata-rata Berat Sawit
    $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0; // OK (Menggunakan $totalPalmWeight yang benar)
    
    // 6. Chart Produksi Sawit (Menggunakan CatatanPanen dan Filtered)
    $dailyPalmWeight = \App\Models\CatatanPanen::selectRaw('DATE(tanggal) as date, SUM(berat_kg) as total_weight')
        ->whereBetween('tanggal', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereHas('pegawai', fn($q) => $q->where('role', 'user')) // <-- PERBAIKAN: Menggunakan CatatanPanen dan 'pegawai'
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Produksi per role (Menggunakan Attendance. Harus difilter)
    $palmWeightByRole = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->whereHas('user', fn($q) => $q->where('role', 'user')) // <-- PERBAIKAN: Filter Ditambahkan
        ->get()
        ->groupBy('user.role')
        ->map(fn($items) => [
            'total_weight' => $items->sum('palm_weight'),
            'total_workers' => $items->unique('user_id')->count(),
            'avg_weight' => $items->unique('user_id')->count() > 0
                ? $items->sum('palm_weight') / $items->unique('user_id')->count()
                : 0,
        ]);

    // Top pekerja (Difilter keras ke 'user')
    $topPerformers = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('palm_weight')
        ->whereHas('user', fn($q) => $q->where('role', 'user')) // <-- PERBAIKAN: Filter Ditambahkan
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
