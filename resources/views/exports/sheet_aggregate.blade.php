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

        <th rowspan="2">Total Tandan</th>
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
        @foreach($users as $u)
            @php
                $total_tandan = 0;
                $total_berat  = 0;
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
                            $total_tandan += (int) $row->jumlah_tandan;
                            $total_berat  += (float) $row->berat_kg;
                        }

                        if(!$mark && isset($absensi[$key])) {
                            $mark = 'T';
                        }
                    @endphp

                    <td>{{ $mark }}</td>
                @endforeach

                <td>{{ $total_tandan }}</td>
                <td>{{ number_format($total_berat, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
