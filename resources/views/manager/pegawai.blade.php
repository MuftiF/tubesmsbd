@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Manager</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Kelola Pegawai</h1>
                    <p class="text-sm text-gray-500 mt-1">Manajemen data pegawai perusahaan</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf4f1] text-[#1f4a3d] p-4 px-5 rounded-2xl mb-5 border border-[#2c5e4e]/20 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{!! session('success') !!}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 text-red-800 p-4 px-5 rounded-2xl mb-5 border border-red-200 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('warning'))
        <div class="flex items-center gap-3 bg-amber-50 text-amber-800 p-4 px-5 rounded-2xl mb-5 border border-amber-200 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="flex items-start gap-3 bg-red-50 text-red-800 p-4 px-5 rounded-2xl mb-5 border border-red-200 text-sm">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <ul class="list-disc ml-4 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form Tambah Pegawai --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#eaf4f1]">
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-700">Tambah Pegawai Baru</h2>
            </div>
            <form action="{{ route('manager.pegawai.tambah') }}" method="POST" id="tambahForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Masukkan nama"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">No HP</label>
                        <input type="tel" name="no_hp" required placeholder="081234567890" pattern="^[0-9]{10,13}$" title="Masukkan 10-13 digit angka" value="{{ old('no_hp') }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                        <div class="text-xs text-gray-400 mt-1">10-13 digit angka</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Role</label>
                        <select name="role" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pekerja (User)</option>
                            <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="cleaning" {{ old('role') == 'cleaning' ? 'selected' : '' }}>Cleaning Service</option>
                            <option value="kantoran" {{ old('role') == 'kantoran' ? 'selected' : '' }}>Staff Kantor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                        <div class="text-xs text-gray-400 mt-1">Minimal 6 karakter</div>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit"
                        class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Pegawai
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Pegawai --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-700">Daftar Pegawai</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">No HP</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Role</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Bergabung</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
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
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-[#eaf4f1] rounded-xl flex items-center justify-center font-bold text-[#2c5e4e] text-sm flex-shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800 text-sm">{{ $p->name }}</div>
                                        <div class="text-xs text-gray-400">ID: {{ $p->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->no_hp ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf4f1] text-[#2c5e4e]">
                                    @switch($p->role)
                                        @case('user') Pekerja @break
                                        @case('security') Security @break
                                        @case('cleaning') Cleaning Service @break
                                        @case('kantoran') Staff Kantor @break
                                        @default {{ ucfirst($p->role) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal({{ $p }})"
                                        class="border border-gray-200 text-gray-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-[#eaf4f1] hover:border-[#2c5e4e] hover:text-[#2c5e4e] transition">
                                        Edit
                                    </button>
                                    @if($hasRiwayat)
                                    <button onclick="openForceDeleteModal({{ $p->id }}, '{{ $p->name }}')"
                                        class="border border-red-200 text-red-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-400 transition">
                                        Hapus Paksa
                                    </button>
                                    @else
                                    <form action="{{ route('manager.pegawai.hapus', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="border border-red-200 text-red-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-400 transition">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-14 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="font-semibold text-sm text-gray-500">Tidak ada data pegawai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 invisible opacity-0 transition-all duration-200">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">Edit Pegawai</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">No HP</label>
                    <input type="tel" id="edit_no_hp" name="no_hp" required pattern="^[0-9]{10,13}$" title="10-13 digit angka"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    <div class="text-xs text-gray-400 mt-1">10-13 digit angka</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Role</label>
                    <select id="edit_role" name="role" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                        <option value="user">Pekerja</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning Service</option>
                        <option value="kantoran">Staff Kantor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Password (Opsional)</label>
                    <input type="password" id="edit_password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    <div class="text-xs text-gray-400 mt-1">Isi hanya jika ingin mengganti password (minimal 6 karakter)</div>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <button type="button" onclick="closeEditModal()"
                    class="bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Paksa --}}
<div id="forceDeleteModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 invisible opacity-0 transition-all duration-200">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-red-600">Hapus Paksa Pegawai</h3>
            </div>
            <button type="button" onclick="closeForceDeleteModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="forceDeleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="p-6">
                <p class="text-sm text-gray-700 mb-4">Anda akan menghapus pegawai: <strong id="deleteEmployeeName" class="text-red-600"></strong></p>
                <div class="bg-red-50 rounded-xl p-4 mb-5 border border-red-200">
                    <h4 class="text-sm font-semibold text-red-700 mb-2">PERINGATAN!</h4>
                    <p class="text-xs text-red-700">Tindakan ini akan menghapus secara permanen:</p>
                    <ul class="list-disc ml-5 text-xs text-red-700 mt-2 space-y-0.5">
                        <li>Semua data absensi pegawai</li>
                        <li>Semua catatan panen pegawai</li>
                        <li>Semua data rapor/evaluasi</li>
                        <li>Data pegawai secara permanen</li>
                    </ul>
                    <p class="text-xs font-bold text-red-700 mt-3">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-700 mb-4 cursor-pointer">
                    <input type="checkbox" name="confirm_delete" value="YA" required class="w-4 h-4 accent-red-600">
                    Saya memahami konsekuensi dan ingin melanjutkan
                </label>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Ketik <strong class="text-red-600">YA</strong> untuk konfirmasi:</label>
                    <input type="text" name="confirm_text" required pattern="YA" title="Harap ketik YA" placeholder="Ketik YA"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <button type="button" onclick="closeForceDeleteModal()"
                    class="bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition">
                    Hapus Paksa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_no_hp').value = data.no_hp || '';
    document.getElementById('edit_role').value = data.role;
    document.getElementById('edit_password').value = '';
    document.getElementById('editForm').action = '/manager/pegawai/' + data.id;
    document.getElementById('editModal').classList.add('visible', 'opacity-100');
    document.getElementById('editModal').classList.remove('invisible', 'opacity-0');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('invisible', 'opacity-0');
    document.getElementById('editModal').classList.remove('visible', 'opacity-100');
}

function openForceDeleteModal(userId, userName) {
    document.getElementById('deleteEmployeeName').textContent = userName;
    document.getElementById('forceDeleteForm').action = '/manager/pegawai/force-delete/' + userId;
    document.getElementById('forceDeleteModal').classList.add('visible', 'opacity-100');
    document.getElementById('forceDeleteModal').classList.remove('invisible', 'opacity-0');
}

function closeForceDeleteModal() {
    document.getElementById('forceDeleteModal').classList.add('invisible', 'opacity-0');
    document.getElementById('forceDeleteModal').classList.remove('visible', 'opacity-100');
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