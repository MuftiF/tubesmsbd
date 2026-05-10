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

    /* ALERT */
    .alert {
        border-radius: 16px;
        padding: 0.9rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8rem;
    }
    .alert-success {
        background: #e3f5e9;
        color: #0f6e3f;
        border-left: 4px solid #2d6a4f;
    }
    .alert-error {
        background: #fee9e6;
        color: #bc3f2c;
        border-left: 4px solid #dc2626;
    }
    .alert-warning {
        background: #fef3c7;
        color: #b45309;
        border-left: 4px solid #eab308;
    }
    .alert i { font-size: 1rem; }

    /* FORM BOX */
    .form-box {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .form-box h2 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1c1c1c;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #eef5f0;
        display: inline-block;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .form-grid { grid-template-columns: repeat(4, 1fr); }
    }
    .form-group label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #57534e;
        display: block;
        margin-bottom: 0.3rem;
    }
    .form-group input,
    .form-group select {
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
    .form-group input:focus,
    .form-group select:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
    }
    .form-hint {
        font-size: 0.65rem;
        color: #a8a29e;
        margin-top: 0.25rem;
    }
    .btn-primary {
        background: #2d6a4f;
        color: white;
        padding: 0.55rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover { background: #235c44; }

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
    .badge-user { background: #eef5f0; color: #2d6a4f; }
    .badge-security { background: #eef2ff; color: #1e40af; }
    .badge-cleaning { background: #fef3c7; color: #b45309; }
    .badge-kantoran { background: #f3e8ff; color: #6b21a5; }

    /* ACTION BUTTONS */
    .btn-edit {
        background: transparent;
        border: 1px solid #e7e5e4;
        color: #57534e;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 0.5rem;
    }
    .btn-edit:hover {
        background: #eef5f0;
        border-color: #2d6a4f;
        color: #2d6a4f;
    }
    .btn-delete {
        background: transparent;
        border: 1px solid #fee9e6;
        color: #bc3f2c;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-delete:hover {
        background: #fee9e6;
        border-color: #dc2626;
    }
    .btn-force-delete {
        background: transparent;
        border: 1px solid #fee9e6;
        color: #dc2626;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-force-delete:hover {
        background: #fee9e6;
        border-color: #dc2626;
    }

    /* MODAL */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        visibility: hidden;
        opacity: 0;
        transition: all 0.2s;
    }
    .modal-overlay.active {
        visibility: visible;
        opacity: 1;
    }
    .modal-container {
        background: white;
        border-radius: 24px;
        width: 100%;
        max-width: 520px;
        margin: 1rem;
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0ee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1c1c1c;
        margin: 0;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #a8a29e;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f0f0ee;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* WARNING BOX */
    .warning-box {
        background: #fee9e6;
        border-radius: 16px;
        padding: 1rem;
        margin: 1rem 0;
        border-left: 4px solid #dc2626;
    }
    .warning-box h4 {
        font-size: 0.8rem;
        font-weight: 700;
        color: #bc3f2c;
        margin: 0 0 0.5rem 0;
    }
    .warning-box p {
        font-size: 0.75rem;
        color: #7f2e1f;
        margin: 0.25rem 0;
    }
    .warning-box ul {
        margin: 0.5rem 0 0 1rem;
        font-size: 0.7rem;
        color: #7f2e1f;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #57534e;
        margin: 1rem 0;
    }
    .checkbox-label input {
        width: 16px;
        height: 16px;
        accent-color: #dc2626;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #a8a29e;
    }
    .empty-state p { font-weight: 600; margin-top: 0.5rem; font-size: 0.85rem; }

    /* FLEX */
    .flex-start { display: flex; align-items: center; gap: 0.75rem; }
    .gap-3 { gap: 0.75rem; }
</style>

<div class="pegawai-wrap">
<div style="max-width:1280px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Kelola Pegawai</h1>
        <p>Manajemen data pegawai perusahaan</p>
    </div>

    <!-- ALERT -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{!! session('success') !!}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">
        <i class="fas fa-clock"></i>
        <span>{{ session('warning') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-times-circle"></i>
        <div>
            <ul style="margin-left:1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- FORM TAMBAH PEGAWAI -->
    <div class="form-box">
        <h2><i class="fas fa-user-plus" style="margin-right:0.5rem;"></i> Tambah Pegawai Baru</h2>
        <form action="{{ route('manager.pegawai.tambah') }}" method="POST" id="tambahForm">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Masukkan nama">
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="tel" name="no_hp" required placeholder="081234567890"
                        pattern="^[0-9]{10,13}$" title="Masukkan 10-13 digit angka"
                        value="{{ old('no_hp') }}">
                    <div class="form-hint">10-13 digit angka</div>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pekerja (User)</option>
                        <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                        <option value="cleaning" {{ old('role') == 'cleaning' ? 'selected' : '' }}>Cleaning Service</option>
                        <option value="kantoran" {{ old('role') == 'kantoran' ? 'selected' : '' }}>Staff Kantor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
                    <div class="form-hint">Minimal 6 karakter</div>
                </div>
            </div>
            <div style="margin-top:1.25rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Tambah Pegawai
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL PEGAWAI -->
    <div class="table-box">
        <div class="table-header">
            <h2><i class="fas fa-users" style="margin-right:0.5rem;"></i> Daftar Pegawai</h2>
        </div>
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawai as $p)
                    @php
                        $hasAttendance = \App\Models\Attendance::where('user_id', $p->id)->exists();
                        $hasPanen = \App\Models\CatatanPanen::where('id_pegawai', $p->id)->exists();
                        $hasRapot = class_exists('\App\Models\Rapot') ? \App\Models\Rapot::where('id_user', $p->id)->exists() : false;
                        $hasRiwayat = $hasAttendance || $hasPanen || $hasRapot;
                    @endphp
                    <tr>
                        <td>
                            <div class="flex-start">
                                <div class="avatar">{{ strtoupper(substr($p->name, 0, 1)) }}</div>
                                <div>
                                    <div class="employee-name">{{ $p->name }}</div>
                                    <div class="employee-id">ID: {{ $p->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $p->no_hp ?? '-' }}</td>
                        <td>
                            <span class="badge-role 
                                @if($p->role=='user') badge-user
                                @elseif($p->role=='security') badge-security
                                @elseif($p->role=='cleaning') badge-cleaning
                                @else badge-kantoran @endif">
                                @switch($p->role)
                                    @case('user') Pekerja @break
                                    @case('security') Security @break
                                    @case('cleaning') Cleaning Service @break
                                    @case('kantoran') Staff Kantor @break
                                    @default {{ ucfirst($p->role) }}
                                @endswitch
                            </span>
                        </td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td>
                            <button onclick="openEditModal({{ $p }})" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @if($hasRiwayat)
                                <button onclick="openForceDeleteModal({{ $p->id }}, '{{ $p->name }}')" class="btn-force-delete">
                                    <i class="fas fa-trash-alt"></i> Hapus Paksa
                                </button>
                            @else
                                <form action="{{ route('manager.pegawai.hapus', $p->id) }}" method="POST" style="display:inline;" 
                                      onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-user-slash" style="font-size:2rem;"></i>
                            <p>Tidak ada data pegawai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Pegawai</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>No HP</label>
                    <input type="tel" id="edit_no_hp" name="no_hp" required pattern="^[0-9]{10,13}$" title="10-13 digit angka">
                    <div class="form-hint">10-13 digit angka</div>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Role</label>
                    <select id="edit_role" name="role" required>
                        <option value="user">Pekerja</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning Service</option>
                        <option value="kantoran">Staff Kantor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password (Opsional)</label>
                    <input type="password" id="edit_password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah">
                    <div class="form-hint">Isi hanya jika ingin mengganti password (minimal 6 karakter)</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" style="background:#f0f0ee; border:none; padding:0.5rem 1rem; border-radius:12px; font-weight:600;">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS PAKSA -->
<div id="forceDeleteModal" class="modal-overlay">
    <div class="modal-container" style="max-width:500px;">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i> Hapus Paksa Pegawai</h3>
            <button type="button" class="modal-close" onclick="closeForceDeleteModal()">&times;</button>
        </div>
        <form id="forceDeleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body">
                <p style="margin-bottom: 1rem;">Anda akan menghapus pegawai: <strong id="deleteEmployeeName" style="color:#dc2626;"></strong></p>
                <div class="warning-box">
                    <h4><i class="fas fa-skull-crosswalk"></i> PERINGATAN!</h4>
                    <p>Tindakan ini akan menghapus secara permanen:</p>
                    <ul>
                        <li>Semua data absensi pegawai</li>
                        <li>Semua catatan panen pegawai</li>
                        <li>Semua data rapor/evaluasi</li>
                        <li>Data pegawai secara permanen</li>
                    </ul>
                    <p style="margin-top:0.5rem;"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                </div>
                <label class="checkbox-label">
                    <input type="checkbox" name="confirm_delete" value="YA" required>
                    Saya memahami konsekuensi dan ingin melanjutkan
                </label>
                <div class="form-group">
                    <label>Ketik <strong style="color:#dc2626;">YA</strong> untuk konfirmasi:</label>
                    <input type="text" name="confirm_text" required pattern="YA" title="Harap ketik YA" placeholder="Ketik YA">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeForceDeleteModal()" style="background:#f0f0ee; border:none; padding:0.5rem 1rem; border-radius:12px; font-weight:600;">Batal</button>
                <button type="submit" style="background:#dc2626; border:none; color:white; padding:0.5rem 1rem; border-radius:12px; font-weight:600; cursor:pointer;">Hapus Paksa</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<script>
function openEditModal(data) {
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_no_hp').value = data.no_hp || '';
    document.getElementById('edit_role').value = data.role;
    document.getElementById('edit_password').value = '';
    document.getElementById('editForm').action = '/manager/pegawai/' + data.id;
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function openForceDeleteModal(userId, userName) {
    document.getElementById('deleteEmployeeName').textContent = userName;
    document.getElementById('forceDeleteForm').action = '/manager/pegawai/force-delete/' + userId;
    document.getElementById('forceDeleteModal').classList.add('active');
}

function closeForceDeleteModal() {
    document.getElementById('forceDeleteModal').classList.remove('active');
    const form = document.getElementById('forceDeleteForm');
    if (form) form.reset();
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
document.getElementById('forceDeleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeForceDeleteModal();
});

document.getElementById('forceDeleteForm').addEventListener('submit', function(e) {
    const confirmCheck = this.querySelector('input[name="confirm_delete"]');
    const confirmText = this.querySelector('input[name="confirm_text"]');
    if (!confirmCheck.checked) {
        e.preventDefault();
        alert('Harap centang konfirmasi terlebih dahulu!');
        return;
    }
    if (confirmText.value !== 'YA') {
        e.preventDefault();
        alert('Harap ketik "YA" untuk konfirmasi!');
        confirmText.focus();
        return;
    }
    if (!confirm('Anda yakin ingin menghapus pegawai ini beserta semua riwayatnya? Tindakan ini tidak dapat dibatalkan!')) {
        e.preventDefault();
    }
});

// Validasi No HP hanya angka
document.querySelectorAll('input[name="no_hp"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0,13);
    });
});
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const noHp = this.querySelector('input[name="no_hp"]');
        if (noHp && noHp.value && !/^[0-9]{10,13}$/.test(noHp.value)) {
            e.preventDefault();
            alert('No HP harus terdiri dari 10-13 digit angka.');
            noHp.focus();
        }
    });
});
</script>
@endsection