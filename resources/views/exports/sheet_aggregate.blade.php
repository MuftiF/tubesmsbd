<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #000;
        padding: 4px;
        text-align: center;
    }

    th {
        background: #d9e1f2;
        font-weight: bold;
        white-space: nowrap;
    }

    td:first-child,
    td:nth-child(2),
    td:nth-child(3) {
        text-align: left;
        padding-left: 6px;
        font-weight: 500;
    }
</style>

<table>
    <thead>
        {{-- ====== KELOMPOK TANGGAL PER BULAN ====== --}}
        @php
            $months = [];
            foreach ($dates as $d) {
                $carbon = \Carbon\Carbon::parse($d);
                $monthKey = $carbon->format('Y-m');
                $months[$monthKey][] = $d;
            }
            
            // Filter user: hanya yang bukan admin atau manager
            $filtered_users = $users->filter(function($user) {
                return !in_array($user->role, ['admin', 'manager']);
            });
        @endphp

        {{-- ====== HEADER BARIS 1: Nama Pegawai + Bulan ====== --}}
        <tr>
            <th rowspan="2">Nama Pegawai</th>
            <th rowspan="2">Role</th>
            <th rowspan="2">No HP</th>

            @foreach($months as $monthKey => $ds)
                @php
                    $m = \Carbon\Carbon::parse($ds[0])->translatedFormat('F Y');
                    $countDays = count($ds);
                @endphp

                <th colspan="{{ $countDays }}">{{ $m }}</th>
            @endforeach

            {{-- Hanya Total Berat saja --}}
            <th rowspan="2">Total Berat (kg)</th>
        </tr>

        {{-- ====== HEADER BARIS 2: Nomor Tanggal ====== --}}
        <tr>
            @foreach($months as $monthKey => $ds)
                @foreach($ds as $d)
                    <th>{{ \Carbon\Carbon::parse($d)->format('d') }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>

    <tbody>
        {{-- Tampilkan hanya user yang bukan admin/manager --}}
        @foreach($filtered_users as $u)
            @php
                $total_berat  = 0; // Hanya inisialisasi total berat
            @endphp

            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>{{ $u->no_hp }}</td>

                @foreach($dates as $d)
                    @php
                        $mark = '';
                        $key  = $u->id . '-' . $d;

                        if(isset($panen[$key])) {
                            $row = $panen[$key][0];
                            $mark = 'H';
                            $total_berat  += (float) $row->berat_kg; // Hanya tambah berat
                        }

                        if(!$mark && isset($absensi[$key])) {
                            $mark = 'T';
                        }
                    @endphp

                    {{-- ====== WARNA SESUAI STATUS ====== --}}
                    <td 
                        @php
                            if ($mark === 'H') {
                                echo 'style="background:#c6efce; color:#006100;"'; // Hijau
                            } elseif ($mark === 'T') {
                                echo 'style="background:#ffeb9c; color:#9c6500;"'; // Kuning
                            } else {
                                echo 'style="background:#f2b3b3; color:#7f0000;"'; // Merah (Alpha)
                            }
                        @endphp
                    >
                        {{ $mark ?: 'A' }}
                    </td>
                @endforeach

                {{-- Hanya tampilkan total berat --}}
                <td>{{ number_format($total_berat, 2) }}</td>
            </tr>
        @endforeach
        
        {{-- Pesan jika tidak ada data --}}
        @if($filtered_users->count() === 0)
            <tr>
                <td colspan="{{ count($dates) + 4 }}" style="text-align: center; padding: 20px;">
                    Tidak ada data pegawai yang ditampilkan (admin dan manager tidak termasuk)
                </td>
            </tr>
        @endif
    </tbody>
</table>