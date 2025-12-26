@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Pegawai</h1>
        <p class="text-gray-500 mt-2">Manajemen data pegawai perusahaan</p>
    </div>

    {{-- ALERT --}}
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-600 text-green-700 px-4 py-3 rounded-lg mb-4 shadow">
        {!! session('success') !!}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-600 text-red-700 px-4 py-3 rounded-lg mb-4 shadow">
        {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-600 text-yellow-700 px-4 py-3 rounded-lg mb-4 shadow">
        {{ session('warning') }}
    </div>
@endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 text-red-700 px-4 py-3 rounded-lg mb-4 shadow">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM TAMBAH --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-10 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-5">Tambah Pegawai Baru</h2>

        <form action="{{ route('manager.pegawai.tambah') }}" method="POST" id="tambahForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="tel" name="no_hp" required placeholder="Contoh: 081234567890"
                        pattern="^[0-9]{10,13}$" title="Masukkan 10-13 digit angka"
                        value="{{ old('no_hp') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-1">10-13 digit angka (contoh: 081234567890)</p>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pekerja (User)</option>
                        <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                        <option value="cleaning" {{ old('role') == 'cleaning' ? 'selected' : '' }}>Cleaning Service</option>
                        <option value="kantoran" {{ old('role') == 'kantoran' ? 'selected' : '' }}>Staff Kantor</option>
                    </select>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>

            </div>

            <div class="mt-5">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                    Tambah Pegawai
                </button>
            </div>
        </form>
    </div>


    {{-- TABEL PEGAWAI --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Daftar Pegawai</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                {{-- HEAD --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No HP</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Riwayat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($pegawai as $p)
                    @php
                        $hasAttendance = \App\Models\Attendance::where('user_id', $p->id)->exists();
                        $hasPanen = \App\Models\CatatanPanen::where('id_pegawai', $p->id)->exists();
                        $hasRapot = class_exists('\App\Models\Rapot') ? \App\Models\Rapot::where('id_user', $p->id)->exists() : false;
                        $hasRiwayat = $hasAttendance || $hasPanen || $hasRapot;
                    @endphp
                    
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-800">{{ $p->name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $p->id }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- No HP --}}
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $p->no_hp ?? '-' }}
                        </td>

                        {{-- Role --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                @if($p->role=='user') bg-green-100 text-green-800
                                @elseif($p->role=='security') bg-blue-100 text-blue-800
                                @elseif($p->role=='cleaning') bg-yellow-100 text-yellow-800
                                @else bg-purple-100 text-purple-800 @endif">
                                @switch($p->role)
                                    @case('user')
                                        Pekerja
                                        @break
                                    @case('security')
                                        Security
                                        @break
                                    @case('cleaning')
                                        Cleaning Service
                                        @break
                                    @case('kantoran')
                                        Staff Kantor
                                        @break
                                    @default
                                        {{ ucfirst($p->role) }}
                                @endswitch
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $p->created_at->format('d M Y') }}
                        </td>

                        {{-- Riwayat --}}
                        <td class="px-6 py-4">
                            @if($hasRiwayat)
                                <div class="flex flex-col space-y-1">
                                    @if($hasAttendance)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Absensi
                                        </span>
                                    @endif
                                    @if($hasPanen)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Panen
                                        </span>
                                    @endif
                                    @if($hasRapot)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                            Rapot
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada riwayat</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Tombol Edit --}}
                                <button onclick="openEditModal({{ $p }})"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm px-3 py-1 bg-indigo-50 rounded hover:bg-indigo-100 transition">
                                    Edit
                                </button>

                                {{-- Tombol Hapus berdasarkan kondisi --}}
                                @if($hasRiwayat)
                                    {{-- Jika ada riwayat, tampilkan modal konfirmasi hapus paksa --}}
                                    <button onclick="openForceDeleteModal({{ $p->id }}, '{{ $p->name }}')"
                                        class="text-red-600 hover:text-red-800 font-semibold text-sm px-3 py-1 bg-red-50 rounded hover:bg-red-100 transition">
                                        Hapus Paksa
                                    </button>
                                @else
                                    {{-- Jika tidak ada riwayat, hapus biasa --}}
                                    <form action="{{ route('manager.pegawai.hapus', $p->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 font-semibold text-sm px-3 py-1 bg-red-50 rounded hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                            Tidak ada data pegawai.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>


{{-- MODAL EDIT --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50">
    <div class="relative top-24 mx-auto w-full max-w-xl bg-white rounded-xl shadow-lg p-6">

        <h3 class="text-xl font-bold text-gray-800 mb-5">Edit Pegawai</h3>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="tel" id="edit_no_hp" name="no_hp" required 
                        pattern="^[0-9]{10,13}$" title="Masukkan 10-13 digit angka"
                        placeholder="Contoh: 081234567890" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">10-13 digit angka</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="edit_role" name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="user">Pekerja</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="kantoran">Staff Kantor</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (Opsional)</label>
                    <input type="password" id="edit_password" name="password" minlength="6"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Isi hanya jika ingin mengganti password (minimal 6 karakter)</p>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold text-gray-700">
                    Batal
                </button>
                <button class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS PAKSA --}}
<div id="forceDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="relative top-24 mx-auto w-full max-w-md bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Hapus Paksa Pegawai</h3>
        
        <div class="mb-6">
            <p class="text-gray-600 text-center mb-4">
                Anda akan menghapus pegawai:
                <span id="deleteEmployeeName" class="font-bold text-red-600"></span>
            </p>
            
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-red-800">PERINGATAN!</h4>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Tindakan ini akan menghapus:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Semua data absensi pegawai</li>
                                <li>Semua catatan panen pegawai</li>
                                <li>Semua data rapor/evaluasi</li>
                                <li>Data pegawai secara permanen</li>
                            </ul>
                            <p class="mt-2 font-bold">Tindakan ini tidak dapat dibatalkan!</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="forceDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="confirm_delete" value="YA" required
                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            Saya memahami konsekuensi dan ingin melanjutkan
                        </span>
                    </label>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ketik <span class="font-bold text-red-600">YA</span> untuk konfirmasi:
                    </label>
                    <input type="text" name="confirm_text" required
                        pattern="YA"
                        title="Harap ketik YA untuk konfirmasi"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 outline-none"
                        placeholder="Ketik YA">
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeForceDeleteModal()" 
                            class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold text-gray-700">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md transition">
                        Hapus Paksa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function openEditModal(data) {
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_no_hp').value = data.no_hp || '';
    document.getElementById('edit_role').value = data.role;
    document.getElementById('edit_password').value = '';

    document.getElementById('editForm').action = '/manager/pegawai/' + data.id;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openForceDeleteModal(userId, userName) {
    document.getElementById('deleteEmployeeName').textContent = userName;
    
    const form = document.getElementById('forceDeleteForm');
    form.action = '/manager/pegawai/force-delete/' + userId;
    
    document.getElementById('forceDeleteModal').classList.remove('hidden');
}

function closeForceDeleteModal() {
    document.getElementById('forceDeleteModal').classList.add('hidden');
    // Reset form
    const form = document.getElementById('forceDeleteModal');
    form.reset();
}

// Close modal by clicking overlay
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('forceDeleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeForceDeleteModal();
});

// Validasi form force delete
document.getElementById('forceDeleteForm').addEventListener('submit', function(e) {
    const confirmCheckbox = this.querySelector('input[name="confirm_delete"]');
    const confirmText = this.querySelector('input[name="confirm_text"]');
    
    if (!confirmCheckbox.checked) {
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
    
    // Final warning
    if (!confirm('Anda yakin ingin menghapus pegawai ini beserta semua riwayatnya? Tindakan ini tidak dapat dibatalkan!')) {
        e.preventDefault();
        return;
    }
});

// Validasi input No HP hanya angka
document.addEventListener('DOMContentLoaded', function() {
    // Validasi form tambah
    const tambahForm = document.getElementById('tambahForm');
    const noHpInputTambah = tambahForm.querySelector('input[name="no_hp"]');
    
    noHpInputTambah.addEventListener('input', function(e) {
        // Hanya angka yang diperbolehkan
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Batasi maksimal 13 digit
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });
    
    // Validasi form edit
    const editNoHpInput = document.getElementById('edit_no_hp');
    editNoHpInput.addEventListener('input', function(e) {
        // Hanya angka yang diperbolehkan
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Batasi maksimal 13 digit
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });
    
    // Validasi sebelum submit
    tambahForm.addEventListener('submit', function(e) {
        const noHpValue = noHpInputTambah.value;
        const noHpPattern = /^[0-9]{10,13}$/;
        
        if (!noHpPattern.test(noHpValue)) {
            e.preventDefault();
            alert('No HP harus terdiri dari 10-13 digit angka.');
            noHpInputTambah.focus();
        }
    });
    
    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function(e) {
        const noHpValue = editNoHpInput.value;
        const noHpPattern = /^[0-9]{10,13}$/;
        
        if (!noHpPattern.test(noHpValue)) {
            e.preventDefault();
            alert('No HP harus terdiri dari 10-13 digit angka.');
            editNoHpInput.focus();
        }
    });
});
</script>
@endsection