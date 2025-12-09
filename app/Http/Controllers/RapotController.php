<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rapot;
use App\Models\Attendance;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RapotExport;

class RapotController extends Controller
{
    /**
     * ADMIN - LIST RAPOT
     */
    public function index()
    {
        $rapots = Rapot::with('user')->latest()->get();
        $users = User::whereIn('role', ['security', 'cleaning', 'kantoran', 'user'])->get();
        
        return view('rapot.admin.admin', compact('rapots', 'users'));
    }

    /**
     * GENERATE RAPOT
     */
    public function generate(Request $request, User $user = null)
    {
        $request->validate([
            'periode_start' => 'required|date',
            'periode_end' => 'required|date|after_or_equal:periode_start',
            'catatan' => 'nullable|string|max:500'
        ]);

        if (!$user) {
            $user = User::findOrFail($request->user_id);
        }

        $start = Carbon::parse($request->periode_start)->startOfDay();
        $end = Carbon::parse($request->periode_end)->endOfDay();

        $absen = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->get();

        $totalJam = 0;
        $hariKerja = 0;
        $detailAbsen = [];

        foreach ($absen as $a) {
            if ($a->check_in && $a->check_out) {
                $jamHariIni = Carbon::parse($a->check_out)->diffInHours(Carbon::parse($a->check_in));
                $totalJam += $jamHariIni;
                $hariKerja++;

                $detailAbsen[] = [
                    'tanggal' => $a->date,
                    'check_in' => $a->check_in,
                    'check_out' => $a->check_out,
                    'jam_kerja' => $jamHariIni,
                    'status' => $a->status
                ];
            }
        }

        $rapot = Rapot::create([
            'id_user'       => $user->id,
            'periode'       => "{$start->format('d M Y')} - {$end->format('d M Y')}",
            'periode_start' => $start,
            'periode_end'   => $end,
            'nilai'         => $totalJam,
            'rata_rata'     => $hariKerja > 0 ? $totalJam / $hariKerja : 0,
            'hari_kerja'    => $hariKerja,
            'catatan'       => $request->catatan ?? "Total jam kerja periode tersebut",
            'detail_absen'  => json_encode($detailAbsen),
            'generated_at'  => now(),
        ]);

        return back()->with('success', "Rapot untuk {$user->name} berhasil dibuat!");
    }

    /**
     * GENERATE BATCH RAPOT
     */
    public function generateBatch(Request $request)
    {
        $request->validate([
            'user_ids'      => 'required|array',
            'user_ids.*'    => 'exists:users,id',
            'periode_start' => 'required|date',
            'periode_end'   => 'required|date|after_or_equal:periode_start',
        ]);

        $start = Carbon::parse($request->periode_start)->startOfDay();
        $end   = Carbon::parse($request->periode_end)->endOfDay();

        $count = 0;

        foreach ($request->user_ids as $id) {
            $user = User::find($id);

            $absen = Attendance::where('user_id', $id)
                ->whereBetween('date', [$start, $end])
                ->get();

            $totalJam = 0;
            $hariKerja = 0;

            foreach ($absen as $a) {
                if ($a->check_in && $a->check_out) {
                    $totalJam += Carbon::parse($a->check_out)->diffInHours(Carbon::parse($a->check_in));
                    $hariKerja++;
                }
            }

            Rapot::firstOrCreate([
                'id_user' => $user->id,
                'periode_start' => $start,
                'periode_end' => $end,
            ], [
                'periode'      => "{$start->format('d M Y')} - {$end->format('d M Y')}",
                'nilai'        => $totalJam,
                'hari_kerja'   => $hariKerja,
                'rata_rata'    => $hariKerja > 0 ? $totalJam / $hariKerja : 0,
                'catatan'      => 'Batch generated',
                'generated_at' => now(),
            ]);

            $count++;
        }

        return back()->with('success', "Berhasil generate rapot untuk {$count} user!");
    }

    /**
     * USER RAPOT LIST
     */
    public function indexUser()
    {
        $rapots = Rapot::where('id_user', auth()->id())
    ->orderBy('periode', 'desc')
    ->get();


        return view('rapot.user', compact('rapots'));
    }

    /**
     * DETAIL RAPOT
     */
    public function show(Rapot $rapot)
    {
        if (!auth()->check() || (auth()->user()->role != 'admin' && $rapot->id_user != auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $detailAbsen = json_decode($rapot->detail_absen, true) ?? [];
        return view('rapot.show', compact('rapot', 'detailAbsen'));
    }

    /**
     * EDIT RAPOT
     */
    public function edit(Rapot $rapot)
    {
        return view('rapot.edit', compact('rapot'));
    }

    /**
     * UPDATE RAPOT
     */
    public function update(Request $request, Rapot $rapot)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
            'nilai'   => 'required|numeric|min:0',
        ]);

        $rapot->update($request->only('catatan', 'nilai'));

        return redirect()->route('rapot.index')
            ->with('success', "Rapot berhasil diperbarui!");
    }

    /**
     * DELETE RAPOT
     */
    public function destroy(Rapot $rapot)
    {
        $rapot->delete();
        return back()->with('success', 'Rapot berhasil dihapus!');
    }

    /**
     * EXPORT PDF
     */
    public function exportPDF(Rapot $rapot)
    {
        $detailAbsen = json_decode($rapot->detail_absen, true) ?? [];
        
        $pdf = PDF::loadView('rapot.export.pdf', compact('rapot', 'detailAbsen'))
            ->setPaper('A4', 'portrait');
        
        return $pdf->download("Rapot_{$rapot->user->name}_{$rapot->periode}.pdf");
    }

    /**
     * EXPORT EXCEL (Single)
     */
    public function exportExcel(Rapot $rapot)
    {
        return Excel::download(new RapotExport($rapot), "Rapot_{$rapot->user->name}_{$rapot->periode}.xlsx");
    }

    /**
     * EXPORT SEMUA RAPOT
     */
    public function exportAllExcel(Request $request)
    {
        $rapots = Rapot::with('user')->get();
        return Excel::download(new RapotExport($rapots), "Semua_Rapot.xlsx");
    }

    /**
     * REGENERATE RAPOT
     */
    public function regenerate(Rapot $rapot)
    {
        $user = $rapot->user;

        $absen = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$rapot->periode_start, $rapot->periode_end])
            ->get();

        $totalJam = 0;
        $hariKerja = 0;
        $detailAbsen = [];

        foreach ($absen as $a) {
            if ($a->check_in && $a->check_out) {
                $jam = Carbon::parse($a->check_out)->diffInHours(Carbon::parse($a->check_in));
                $totalJam += $jam;
                $hariKerja++;

                $detailAbsen[] = [
                    'tanggal' => $a->date,
                    'check_in' => $a->check_in,
                    'check_out' => $a->check_out,
                    'jam_kerja' => $jam,
                    'status' => $a->status
                ];
            }
        }

        $rapot->update([
            'nilai'        => $totalJam,
            'rata_rata'    => $hariKerja > 0 ? $totalJam / $hariKerja : 0,
            'hari_kerja'   => $hariKerja,
            'detail_absen' => json_encode($detailAbsen),
            'regenerated_at' => now(),
        ]);

        return back()->with('success', 'Rapot berhasil di-regenerate!');
    }
}
