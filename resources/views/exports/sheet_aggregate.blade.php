<<<<<<< HEAD
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
=======
<table border="1" cellspacing="0" cellpadding="4">
>>>>>>> 15a965b40eda1800edd7bafa05bcd892de044a4f
    <thead>
        <tr>
            <th>Nama Pegawai</th>
            <th>Role</th>
            <th>No HP</th>

            @foreach($dates as $d)
                <th>{{ \Carbon\Carbon::parse($d)->format('d') }}</th>
            @endforeach

            <th>Total Tandan</th>
            <th>Total Berat (kg)</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $u)
            @php
                $total_tandan = 0;
<<<<<<< HEAD
                $total_berat = 0;
=======
                $total_berat  = 0;
>>>>>>> 15a965b40eda1800edd7bafa05bcd892de044a4f
            @endphp

            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>{{ $u->no_hp }}</td>

                @foreach($dates as $d)
<<<<<<< HEAD
                    @php
                        $mark = '';

                        // Cek panen
                        if(isset($panen[$u->id])) {
                            $foundPanen = collect($panen[$u->id])->firstWhere('tanggal', $d);
                            if($foundPanen) {
                                $mark = 'H'; // Panen
                                $total_tandan += (int) ($foundPanen['jumlah_tandan'] ?? 0);
                                $total_berat  += (float) ($foundPanen['berat_kg'] ?? 0);
                            }
                        }

                        // Kalau tidak panen, cek absensi
                        if(!$mark && isset($absensi[$u->id])) {
                            $foundAbsen = collect($absensi[$u->id])->firstWhere('date', $d);

                            // dianggap hadir kalau:
                            // - status tidak null   ATAU
                            // - check_in tidak null
                            if($foundAbsen && ($foundAbsen->status !== null || $foundAbsen->check_in !== null)) {
                                $mark = 'T';
                            }
                        }

                    @endphp

                    <td>{{ $mark }}</td>
                @endforeach

                <td>{{ $total_tandan }}</td>
                <td>{{ number_format($total_berat, 2) }}</td>
=======

                    @php
                        $mark = '';
                        $key = $u->id . '-' . $d;

                        // CEK PANEN (HADIR PANEN)
                        if(isset($panen[$key])) {
                            $row = $panen[$key][0];
                            $mark = 'H';
                            $total_tandan += (int) $row->jumlah_tandan;
                            $total_berat  += (float) $row->berat_kg;
                        }

                        // CEK ABSENSI (HADIR / TERLAMBAT)
                        if(!$mark && isset($absensi[$key])) {
                            $mark = 'T';
                        }
                    @endphp

                    <td style="text-align:center">{{ $mark }}</td>
                @endforeach

                <td>{{ $total_tandan }}</td>
                <td>{{ $total_berat }}</td>
>>>>>>> 15a965b40eda1800edd7bafa05bcd892de044a4f
            </tr>
        @endforeach
    </tbody>
</table>
