<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\CatatanPanen;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SheetAbsenExport;
use App\Exports\RekapSemuaExport;



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

        // Menggunakan model CatatanPanen untuk menghitung produksi
        $produksiHariIni = CatatanPanen::whereDate('tanggal', $today->toDateString())
            ->sum('berat_kg') ?? 0;

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

        // Ambil data panen hari ini untuk user
        $panenHariIni = CatatanPanen::where('id_pegawai', Auth::id())
            ->whereDate('tanggal', $today->toDateString())
            ->first();

        return view('user.dashboard', compact('absenHariIni', 'panenHariIni'));
    }

    public function managerDashboard()
    {
        $today = now('Asia/Jakarta')->startOfDay();

        $absenHariIni = Attendance::where('user_id', Auth::id())
            ->whereDate('date', $today->toDateString())
            ->first();

        $totalTim = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
        $hadirHariIni = Attendance::whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->count();

        // Menggunakan model CatatanPanen untuk menghitung produksi
        $produksiHariIni = CatatanPanen::whereDate('tanggal', $today->toDateString())
            ->sum('berat_kg') ?? 0;

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

        // Data produktivitas 30 hari terakhir
        $produktivitasData = CatatanPanen::select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('COALESCE(SUM(berat_kg), 0) as total_produksi')
            )
            ->where('tanggal', '>=', Carbon::now('Asia/Jakarta')->subDays(30))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'total_produksi' => (float) $item->total_produksi
                ];
            });

        // Top 5 performer hari ini
        $topPerformers = CatatanPanen::select(
                'users.id',
                'users.name',
                'users.role',
                DB::raw('COALESCE(SUM(catatan_panen.berat_kg), 0) as total_produksi'),
                DB::raw('MAX(attendances.check_in) as check_in_time')
            )
            ->join('users', 'catatan_panen.id_pegawai', '=', 'users.id')
            ->leftJoin('attendances', function($join) use ($today) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereDate('attendances.date', $today->toDateString());
            })
            ->whereDate('catatan_panen.tanggal', $today->toDateString())
            ->whereIn('users.role', ['user', 'security', 'cleaning', 'kantoran'])
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderBy('total_produksi', 'desc')
            ->limit(5)
            ->get();

        // Jika tidak ada data produksi, ambil dari attendance saja untuk ranking
        if ($topPerformers->isEmpty() || $topPerformers->sum('total_produksi') == 0) {
            $topPerformers = Attendance::select(
                    'users.id',
                    'users.name',
                    'users.role',
                    DB::raw('0 as total_produksi'),
                    'attendances.check_in as check_in_time'
                )
                ->join('users', 'attendances.user_id', '=', 'users.id')
                ->whereDate('attendances.date', $today->toDateString())
                ->whereNotNull('attendances.check_in')
                ->whereIn('users.role', ['user', 'security', 'cleaning', 'kantoran'])
                ->orderBy('attendances.check_in', 'asc')
                ->limit(5)
                ->get();
        }

        // Statistik tambahan
        $avgProduksi = $produktivitasData->avg('total_produksi') ?? 0;
        
        $totalProduksiBulanIni = CatatanPanen::whereMonth('tanggal', now('Asia/Jakarta')->month)
            ->whereYear('tanggal', now('Asia/Jakarta')->year)
            ->sum('berat_kg') ?? 0;
        
        $peakProduksi = $produktivitasData->max('total_produksi') ?? 0;

        // Hitung trend produksi
        $trend = 'Stabil';
        if ($produktivitasData->count() >= 2) {
            $latestData = $produktivitasData->last();
            $previousData = $produktivitasData->slice(-2, 1)->first();
            
            if ($latestData && $previousData && $previousData['total_produksi'] > 0) {
                $latest = $latestData['total_produksi'];
                $previous = $previousData['total_produksi'];
                $change = (($latest - $previous) / $previous) * 100;
                
                if ($change > 10) $trend = 'Naik';
                else if ($change < -10) $trend = 'Turun';
            }
        }

        // Aktivitas terbaru - dengan produksi
        $recentActivities = Attendance::with('user')
            ->whereDate('date', $today->toDateString())
            ->whereNotNull('check_in')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
            ->orderBy('check_in', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                // Ambil data produksi untuk user ini hari ini
                $produksi = CatatanPanen::where('id_pegawai', $item->user_id)
                    ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
                    ->sum('berat_kg');
                
                $item->produksi_harian = $produksi;
                return $item;
            });

        return view('manager.dashboard', compact(
            'absenHariIni',
            'totalTim',
            'hadirHariIni',
            'produksiHariIni',
            'totalTerlambat',
            'totalAlpha',
            'produktivitasData',
            'topPerformers',
            'avgProduksi',
            'totalProduksiBulanIni',
            'peakProduksi',
            'trend',
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
            'no_hp' => 'required|string|max:20|unique:users,no_hp',
            'role' => 'required|in:user,security,cleaning,kantoran',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
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
            'no_hp' => 'required|string|max:20|unique:users,no_hp,' . $id,
            'role' => 'required|in:user,security,cleaning,kantoran',
        ]);

        $data = [
            'name' => $request->name,
            'no_hp' => $request->no_hp,
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
            ? Carbon::parse($request->start_date)
            : $today;

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
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

        // Menggunakan model CatatanPanen untuk total berat
        $totalPalmWeight = CatatanPanen::whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->sum('berat_kg') ?? 0;

        $totalHadir = Attendance::whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->whereNotNull('check_in')
            ->distinct('user_id')
            ->count('user_id');

        $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

        // Chart: Kehadiran (7 hari terakhir)
        $chartStartDate = Carbon::parse($endDate)->subDays(6);
        $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
            ->whereBetween('date', [
                $chartStartDate->toDateString(),
                $endDate->toDateString()
            ])
            ->whereNotNull('check_in')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart: Produksi Sawit (7 hari terakhir)
        $dailyPalmWeight = CatatanPanen::selectRaw('DATE(tanggal) as date, SUM(berat_kg) as total_weight')
            ->whereBetween('tanggal', [$chartStartDate->toDateString(), $endDate->toDateString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produksi per Role (menggunakan CatatanPanen dengan relasi pegawai)
        $palmWeightByRole = CatatanPanen::with('pegawai')
            ->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->get()
            ->groupBy('pegawai.role')
            ->map(function ($items) {
                return [
                    'total_weight' => $items->sum('berat_kg'),
                    'total_workers' => $items->unique('id_pegawai')->count(),
                    'avg_weight' => $items->unique('id_pegawai')->count() > 0
                        ? $items->sum('berat_kg') / $items->unique('id_pegawai')->count()
                        : 0,
                ];
            });

        // Top pekerja berdasarkan CatatanPanen
        $topPerformers = CatatanPanen::with('pegawai')
            ->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->selectRaw('id_pegawai, SUM(berat_kg) as total_weight')
            ->groupBy('id_pegawai')
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

        if (CatatanPanen::where('id_pegawai', $id)->exists()) {
            return redirect()->route('manager.pegawai')->with('error', 'Tidak dapat menghapus pegawai yang sudah memiliki riwayat panen!');
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

        // Filter nama/no_hp
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('no_hp', 'like', "%{$request->search}%");
            });
        }

        $attendances = $query->orderBy('check_in', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        // Statistik
        $totalPegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
        $totalHadir = Attendance::whereDate('date', $selectedDate->toDateString())
            ->whereNotNull('check_in')
            ->count();
        $totalTerlambat = Attendance::whereDate('date', $selectedDate->toDateString())
            ->where('status', 'terlambat')
            ->count();

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

    // Query dasar untuk detail tabel
    $attendancesQuery = Attendance::with('user')
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']));

    // Filter by role opsional dari UI
    if ($request->filled('role')) {
        $attendancesQuery->whereHas('user', fn($q) => $q->where('role', $request->role));
    }

    // Statistik
    $totalPegawai = User::whereIn('role', ['user', 'security', 'cleaning', 'kantoran'])->count();
    
    $totalHadir = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->distinct('user_id')
        ->count('user_id');

    // Total berat dari CatatanPanen - hanya untuk user yang masih ada
    $totalPalmWeight = CatatanPanen::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereHas('pegawai', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->sum('berat_kg') ?? 0;

    $averagePalmWeight = $totalHadir > 0 ? $totalPalmWeight / $totalHadir : 0;

    // Data chart kehadiran (7 hari terakhir)
    $chartStartDate = Carbon::parse($endDate)->subDays(6);
    $dailyAttendance = Attendance::selectRaw('DATE(date) as date, COUNT(DISTINCT user_id) as total')
        ->whereBetween('date', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereNotNull('check_in')
        ->whereHas('user', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Data chart produksi sawit (7 hari terakhir)
    $dailyPalmWeight = CatatanPanen::selectRaw('DATE(tanggal) as date, SUM(berat_kg) as total_weight')
        ->whereBetween('tanggal', [$chartStartDate->toDateString(), $endDate->toDateString()])
        ->whereHas('pegawai', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Produksi per role (menggunakan CatatanPanen) - hanya untuk user yang masih ada
    $palmWeightByRole = CatatanPanen::with(['pegawai' => function($query) {
            $query->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']);
        }])
        ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereHas('pegawai', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->get()
        ->filter(function ($item) {
            return !is_null($item->pegawai);
        })
        ->groupBy(function ($item) {
            return $item->pegawai ? $item->pegawai->role : 'unknown';
        })
        ->map(function ($items, $role) {
            $validItems = $items->filter(fn($item) => !is_null($item->pegawai));
            return [
                'total_weight' => $validItems->sum('berat_kg'),
                'total_workers' => $validItems->unique('id_pegawai')->count(),
                'avg_weight' => $validItems->unique('id_pegawai')->count() > 0
                    ? $validItems->sum('berat_kg') / $validItems->unique('id_pegawai')->count()
                    : 0,
            ];
        });

    // Top pekerja berdasarkan CatatanPanen - hanya untuk user yang masih ada
    $topPerformers = CatatanPanen::with(['pegawai' => function($query) {
            $query->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']);
        }])
        ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
        ->whereHas('pegawai', fn($q) => $q->whereIn('role', ['user', 'security', 'cleaning', 'kantoran']))
        ->selectRaw('id_pegawai, SUM(berat_kg) as total_weight')
        ->groupBy('id_pegawai')
        ->orderByDesc('total_weight')
        ->limit(5)
        ->get()
        ->filter(function ($item) {
            return !is_null($item->pegawai);
        })
        ->map(function ($item) {
            return (object) [
                'id' => $item->pegawai->id,
                'name' => $item->pegawai->name,
                'role' => $item->pegawai->role,
                'total_weight' => $item->total_weight
            ];
        });

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

    // Method tambahan untuk user
    public function userRiwayat()
    {
        $userId = Auth::id();
        
        $attendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        $panenHistory = CatatanPanen::where('id_pegawai', $userId)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('user.riwayat', compact('attendances', 'panenHistory'));
    }

    // Method untuk absensi user
    public function userAbsen(Request $request)
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $userId = Auth::id();

        // Cek apakah sudah absen hari ini
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today->toDateString())
            ->first();

        if ($existingAttendance) {
            return redirect()->route('user.dashboard')->with('error', 'Anda sudah absen hari ini!');
        }

        // Hitung keterlambatan
        $checkInTime = now('Asia/Jakarta');
        $jamMasuk = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta');
        $status = $checkInTime->greaterThan($jamMasuk) ? 'terlambat' : 'hadir';

        // Simpan absensi
        Attendance::create([
            'user_id' => $userId,
            'date' => $today->toDateString(),
            'check_in' => $checkInTime->toTimeString(),
            'status' => $status,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Absen berhasil!');
    }

public function exportAllCsv()
{
    $from = request('from');
    $to   = request('to');

    return Excel::download(
        new RekapSemuaExport($from, $to),
        "rekap_semua_{$from}_{$to}.xlsx"
    );
}

public function exportAllCsvAllTime()
{
    return Excel::download(
        new RekapSemuaExport(), // tanpa from dan to -> semua data
        "rekap_semua_data.xlsx"
    );
}

public function exportSheetAbsen()
{
    $from = request('from');
    $to   = request('to');

    return Excel::download(
        new SheetAbsenAggregateExport($from, $to),
        "rekap_absen_per_pegawai_{$from}_{$to}.xlsx"
    );
}



    // Method untuk input panen
    public function userInputPanen(Request $request)
    {
        $request->validate([
            'berat_kg' => 'required|numeric|min:0.1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        CatatanPanen::create([
            'id_pegawai' => Auth::id(),
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'berat_kg' => $request->berat_kg,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Data panen berhasil disimpan!');
    }
}