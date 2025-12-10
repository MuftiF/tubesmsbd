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
                $total_berat = 0;
            @endphp

            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>{{ $u->no_hp }}</td>

                @foreach($dates as $d)
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
            </tr>
        @endforeach
    </tbody>
</table>
