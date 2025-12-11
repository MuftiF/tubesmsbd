<table border="1" cellspacing="0" cellpadding="4">
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
                $total_berat  = 0;
            @endphp

            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>{{ $u->no_hp }}</td>

                @foreach($dates as $d)

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
            </tr>
        @endforeach
    </tbody>
</table>
