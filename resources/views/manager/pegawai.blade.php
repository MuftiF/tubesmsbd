@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Kelola Pegawai</h1>
            <p class="text-sm text-stone-500 mt-1">Manajemen data pegawai perusahaan</p>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-100 text-emerald-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-[#2d6a4f] text-sm">
            <i class="fas fa-check-circle text-sm"></i>
            <span>{!! session('success') !!}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-100 text-red-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-red-600 text-sm">
            <i class="fas fa-exclamation-triangle text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('warning'))
        <div class="flex items-center gap-3 bg-amber-100 text-amber-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-amber-500 text-sm">
            <i class="fas fa-clock text-sm"></i>
            <span>{{ session('warning') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="flex items-start gap-3 bg-red-100 text-red-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-red-600 text-sm">
            <i class="fas fa-times-circle text-sm mt-0.5"></i>
            <div>
                <ul class="list-disc ml-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form Tambah Pegawai --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <h2 class="text-sm font-bold text-stone-800 pb-2 border-b-2 border-emerald-100 inline-flex items-center gap-2">
                <i class="fas fa-user-plus text-[#2d6a4f]"></i> Tambah Pegawai Baru
            </h2>
            <form action="{{ route('manager.pegawai.tambah') }}" method="POST" id="tambahForm" class="mt-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Masukkan nama" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                    </div>
                    <div>
                        <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">No HP</label>
                        <input type="tel" name="no_hp" required placeholder="081234567890" pattern="^[0-9]{10,13}$" title="Masukkan 10-13 digit angka" value="{{ old('no_hp') }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        <div class="text-[0.65rem] text-stone-400 mt-1">10-13 digit angka</div>
                    </div>
                    <div>
                        <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Role</label>
                        <select name="role" required class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pekerja (User)</option>
                            <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="cleaning" {{ old('role') == 'cleaning' ? 'selected' : '' }}>Cleaning Service</option>
                            <option value="kantoran" {{ old('role') == 'kantoran' ? 'selected' : '' }}>Staff Kantor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Password</label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        <div class="text-[0.65rem] text-stone-400 mt-1">Minimal 6 karakter</div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-[#2d6a4f] text-white px-5 py-2 rounded-xl font-semibold text-sm hover:bg-[#235c44] transition inline-flex items-center gap-2">
                        <i class="fas fa-save text-xs"></i> Tambah Pegawai
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Pegawai --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="px-5 pt-4 pb-2 border-b border-stone-100">
                <h2 class="text-sm font-bold text-stone-800 inline-flex items-center gap-2">
                    <i class="fas fa-users text-[#2d6a4f]"></i> Daftar Pegawai
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#fafaf8] border-b border-stone-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Nama</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">No HP</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Role</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Bergabung</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Aksi</th>
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
                        <tr class="border-b border-stone-100 hover:bg-[#fefcf7] transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center font-bold text-[#2d6a4f] text-sm">{{ strtoupper(substr($p->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="font-bold text-stone-800 text-sm">{{ $p->name }}</div>
                                        <div class="text-[0.65rem] text-stone-400">ID: {{ $p->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-stone-600">{{ $p->no_hp ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    @if($p->role=='user') bg-emerald-50 text-[#2d6a4f]
                                    @elseif($p->role=='security') bg-blue-50 text-blue-800
                                    @elseif($p->role=='cleaning') bg-amber-50 text-amber-800
                                    @else bg-purple-50 text-purple-800 @endif">
                                    @switch($p->role)
                                        @case('user') Pekerja @break
                                        @case('security') Security @break
                                        @case('cleaning') Cleaning Service @break
                                        @case('kantoran') Staff Kantor @break
                                        @default {{ ucfirst($p->role) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-stone-500">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <button onclick="openEditModal({{ $p }})" class="border border-stone-200 text-stone-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-emerald-50 hover:border-[#2d6a4f] hover:text-[#2d6a4f] transition mr-2">
                                    <i class="fas fa-edit text-xs"></i> Edit
                                </button>
                                @if($hasRiwayat)
                                <button onclick="openForceDeleteModal({{ $p->id }}, '{{ $p->name }}')" class="border border-red-200 text-red-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-500 transition">
                                    <i class="fas fa-trash-alt text-xs"></i> Hapus Paksa
                                </button>
                                @else
                                <form action="{{ route('manager.pegawai.hapus', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="border border-red-200 text-red-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-500 transition">
                                        <i class="fas fa-trash-alt text-xs"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-stone-400">
                                <i class="fas fa-user-slash text-3xl mb-2 block"></i>
                                <p class="font-semibold text-sm text-stone-500">Tidak ada data pegawai</p>
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
        <div class="flex justify-between items-center px-5 py-4 border-b border-stone-100">
            <h3 class="text-base font-bold text-stone-800 inline-flex items-center gap-2"><i class="fas fa-user-edit"></i> Edit Pegawai</h3>
            <button type="button" onclick="closeEditModal()" class="text-stone-400 hover:text-stone-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" required class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                </div>
                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">No HP</label>
                    <input type="tel" id="edit_no_hp" name="no_hp" required pattern="^[0-9]{10,13}$" title="10-13 digit angka" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                    <div class="text-[0.65rem] text-stone-400 mt-1">10-13 digit angka</div>
                </div>
                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Role</label>
                    <select id="edit_role" name="role" required class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        <option value="user">Pekerja</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning Service</option>
                        <option value="kantoran">Staff Kantor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Password (Opsional)</label>
                    <input type="password" id="edit_password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                    <div class="text-[0.65rem] text-stone-400 mt-1">Isi hanya jika ingin mengganti password (minimal 6 karakter)</div>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                <button type="button" onclick="closeEditModal()" class="bg-stone-100 text-stone-600 px-4 py-2 rounded-xl font-semibold text-sm hover:bg-stone-200 transition">Batal</button>
                <button type="submit" class="bg-[#2d6a4f] text-white px-4 py-2 rounded-xl font-semibold text-sm hover:bg-[#235c44] transition">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Paksa --}}
<div id="forceDeleteModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 invisible opacity-0 transition-all duration-200">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center px-5 py-4 border-b border-stone-100">
            <h3 class="text-base font-bold text-red-600 inline-flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Hapus Paksa Pegawai</h3>
            <button type="button" onclick="closeForceDeleteModal()" class="text-stone-400 hover:text-stone-600 text-xl leading-none">&times;</button>
        </div>
        <form id="forceDeleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="p-5">
                <p class="text-sm text-stone-700 mb-4">Anda akan menghapus pegawai: <strong id="deleteEmployeeName" class="text-red-600"></strong></p>
                <div class="bg-red-50 rounded-xl p-4 mb-5 border-l-4 border-red-600">
                    <h4 class="text-sm font-bold text-red-700 mb-2"><i class="fas fa-skull-crosswalk"></i> PERINGATAN!</h4>
                    <p class="text-xs text-red-700">Tindakan ini akan menghapus secara permanen:</p>
                    <ul class="list-disc ml-5 text-xs text-red-700 mt-2 space-y-0.5">
                        <li>Semua data absensi pegawai</li>
                        <li>Semua catatan panen pegawai</li>
                        <li>Semua data rapor/evaluasi</li>
                        <li>Data pegawai secara permanen</li>
                    </ul>
                    <p class="text-xs font-bold text-red-700 mt-3">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <label class="flex items-center gap-2 text-sm text-stone-700 mb-4">
                    <input type="checkbox" name="confirm_delete" value="YA" required class="w-4 h-4 accent-red-600">
                    Saya memahami konsekuensi dan ingin melanjutkan
                </label>
                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Ketik <strong class="text-red-600">YA</strong> untuk konfirmasi:</label>
                    <input type="text" name="confirm_text" required pattern="YA" title="Harap ketik YA" placeholder="Ketik YA" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                <button type="button" onclick="closeForceDeleteModal()" class="bg-stone-100 text-stone-600 px-4 py-2 rounded-xl font-semibold text-sm hover:bg-stone-200 transition">Batal</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:bg-red-700 transition">Hapus Paksa</button>
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