@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    .pegawai-wrap * {
        font-family: 'Inter', sans-serif;
    }

    .pegawai-wrap {
        background: #f8f6f2;
        min-height: 100vh;
        padding: 2rem 1.5rem;
    }

    /* HEADER */
    .lap-header {
        margin-bottom: 2rem;
        position: relative;
        padding-left: 1rem;
    }
    .lap-header::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #2d6a4f;
        border-radius: 2px;
    }
    .lap-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e1e1e;
        letter-spacing: -0.3px;
        margin: 0;
    }
    .lap-header p {
        font-size: 0.85rem;
        color: #78716c;
        margin-top: 0.25rem;
    }

    /* SUMMARY CARDS */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .summary-cards { grid-template-columns: repeat(5, 1fr); }
    }
    .scard {
        background: white;
        border-radius: 18px;
        padding: 1rem 1.25rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.2s;
        text-align: center;
    }
    .scard .sc-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #a8a29e;
        margin-bottom: 0.5rem;
    }
    .scard .sc-val {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1c1c1c;
        line-height: 1.2;
    }
    .scard-blue .sc-val { color: #2563eb; }
    .scard-emerald .sc-val { color: #2d6a4f; }
    .scard-yellow .sc-val { color: #ca8a04; }
    .scard-red .sc-val { color: #dc2626; }
    .scard-purple .sc-val { color: #7c3aed; }

    /* FILTER BOX */
    .filter-box {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-box label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #57534e;
        display: block;
        margin-bottom: 0.3rem;
    }
    .filter-box input,
    .filter-box select {
        width: 100%;
        border: 1px solid #e7e5e4;
        border-radius: 12px;
        padding: 0.6rem 0.875rem;
        font-size: 0.85rem;
        color: #1c1c1c;
        background: #ffffff;
        transition: all 0.2s;
        outline: none;
    }
    .filter-box input:focus,
    .filter-box select:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
    }

    /* TABLE BOX */
    .table-box {
        background: white;
        border-radius: 20px;
        padding: 0;
        border: 1px solid #e7e5e4;
        overflow: hidden;
    }
    .table-header {
        padding: 1.25rem 1.5rem 0.75rem 1.5rem;
        border-bottom: 1px solid #f0f0ee;
    }
    .table-header h2 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1c1c1c;
        margin: 0;
    }
    .overflow-x-auto {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead tr {
        border-bottom: 1px solid #e9ecee;
        background: #fafaf8;
    }
    thead th {
        text-align: left;
        padding: 0.9rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8d8a84;
        background: #fafaf8;
    }
    tbody tr {
        border-bottom: 1px solid #f0f0ee;
        transition: background 0.15s ease;
    }
    tbody tr:hover {
        background: #fefcf7;
    }
    tbody td {
        padding: 0.9rem 1rem;
        font-size: 0.8rem;
        color: #3c3a36;
        vertical-align: middle;
    }

    /* AVATAR */
    .avatar {
        width: 36px;
        height: 36px;
        background: #eef5f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #2d6a4f;
    }
    .employee-name {
        font-weight: 700;
        color: #1c1c1c;
    }
    .employee-id {
        font-size: 0.65rem;
        color: #a8a29e;
        margin-top: 0.15rem;
    }

    /* BADGES */
    .badge-role {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.25;
    }
    .badge-admin { background: #fee9e6; color: #bc3f2c; }
    .badge-manager { background: #f3e8ff; color: #6b21a5; }
    .badge-user { background: #eef5f0; color: #2d6a4f; }
    .badge-security { background: #eef2ff; color: #1e40af; }
    .badge-cleaning { background: #fef3c7; color: #b45309; }
    .badge-kantoran { background: #f3e8ff; color: #6b21a5; }

    /* EMPTY STATE */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #a8a29e;
    }
    .empty-state p {
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .dash {
        color: #cbcbc4;
    }

    /* FLEX */
    .flex-start {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>

<div class="pegawai-wrap">
<div style="max-width:1280px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Data Pegawai</h1>
        <p>Daftar lengkap pegawai perusahaan</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="scard scard-blue">
            <div class="sc-label">Total Pegawai</div>
            <div class="sc-val">{{ $pegawai->count() }}</div>
        </div>
        <div class="scard scard-emerald">
            <div class="sc-label">Kebun & Panen</div>
            <div class="sc-val">{{ $pegawai->where('role','user')->count() }}</div>
        </div>
        <div class="scard scard-blue">
            <div class="sc-label">Security</div>
            <div class="sc-val">{{ $pegawai->where('role','security')->count() }}</div>
        </div>
        <div class="scard scard-yellow">
            <div class="sc-label">Cleaning</div>
            <div class="sc-val">{{ $pegawai->where('role','cleaning')->count() }}</div>
        </div>
        <div class="scard scard-purple">
            <div class="sc-label">Kantoran</div>
            <div class="sc-val">{{ $pegawai->where('role','kantoran')->count() }}</div>
        </div>
    </div>

    <!-- FILTER BOX -->
    <div class="filter-box">
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div>
                    <label>Cari Pegawai</label>
                    <input type="text" id="searchInput" placeholder="Cari nama atau nomor HP pegawai...">
                </div>
            </div>
            <div>
                <label>Filter Role</label>
                <select id="roleFilter">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user">Kebun & Panen</option>
                    <option value="security">Security</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="kantoran">Kantoran</option>
                </select>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-box">
        <div class="table-header">
            <h2>Daftar Pegawai</h2>
        </div>
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    @foreach($pegawai as $i => $emp)
                    <tr class="employee-row" data-role="{{ $emp->role }}" data-name="{{ strtolower($emp->name) }}" data-phone="{{ strtolower($emp->phone ?? $emp->no_hp ?? '') }}">
                        <td style="font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <div class="flex-start">
                                <div class="avatar">{{ strtoupper(substr($emp->name, 0, 1)) }}</div>
                                <div>
                                    <div class="employee-name">{{ $emp->name }}</div>
                                    <div class="employee-id">ID: {{ $emp->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(!empty($emp->phone) || !empty($emp->no_hp))
                                {{ $emp->phone ?? $emp->no_hp }}
                            @else
                                <span class="dash">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-role 
                                @if($emp->role=='admin') badge-admin
                                @elseif($emp->role=='manager') badge-manager
                                @elseif($emp->role=='user') badge-user
                                @elseif($emp->role=='security') badge-security
                                @elseif($emp->role=='cleaning') badge-cleaning
                                @else badge-kantoran
                                @endif">
                                @if($emp->role == 'user') Kebun & Panen
                                @elseif($emp->role == 'security') Security
                                @elseif($emp->role == 'cleaning') Cleaning
                                @elseif($emp->role == 'kantoran') Staff Kantor
                                @else {{ ucfirst($emp->role) }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $emp->created_at ? $emp->created_at->format('d M Y') : '-' }}</td>
                     </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pegawai->isEmpty())
        <div class="empty-state">
            <p>Tidak ada data pegawai</p>
        </div>
        @endif
    </div>

</div>
</div>

<!-- FILTER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const rows = document.querySelectorAll('.employee-row');

    function filter() {
        const search = searchInput.value.toLowerCase();
        const role = roleFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const phone = row.dataset.phone || '';
            const r = row.dataset.role;

            const matchSearch = name.includes(search) || phone.includes(search);
            const matchRole = !role || r === role;

            row.style.display = (matchSearch && matchRole) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filter);
    if (roleFilter) roleFilter.addEventListener('change', filter);
});
</script>
@endsection