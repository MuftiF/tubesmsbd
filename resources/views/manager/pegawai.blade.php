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
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-600 text-red-700 px-4 py-3 rounded-lg mb-4 shadow">
            {{ session('error') }}
        </div>
    @endif


    {{-- FORM TAMBAH --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-10 border border-gray-100">
        <h2 class="text-xl font-bold text-gray-900 mb-5">Tambah Pegawai Baru</h2>

        <form action="{{ route('manager.pegawai.tambah') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Pilih Role</option>
                        <option value="user">Pekerja (User)</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning Service</option>
                        <option value="kantoran">Staff Kantor</option>
                    </select>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

            </div>

            <div class="mt-5">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($pegawai as $p)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-800">{{ $p->name }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $p->email }}
                        </td>

                        {{-- Role --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                @if($p->role=='user') bg-green-100 text-green-800
                                @elseif($p->role=='security') bg-blue-100 text-blue-800
                                @elseif($p->role=='cleaning') bg-yellow-100 text-yellow-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($p->role) }}
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $p->created_at->format('d M Y') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 flex items-center gap-4 text-sm">

                            <button
                                onclick="openEditModal({{ $p }})"
                                class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                Edit
                            </button>

                            <form action="{{ route('manager.pegawai.hapus',$p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800 font-semibold">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">
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
                    <input type="text" id="edit_name" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="edit_role" name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="user">Pekerja</option>
                        <option value="security">Security</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="kantoran">Kantoran</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (Opsional)</label>
                    <input type="password" id="edit_password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
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


<script>
function openEditModal(data) {
    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_email').value = data.email;
    document.getElementById('edit_role').value = data.role;
    document.getElementById('edit_password').value = '';

    document.getElementById('editForm').action = '/manager/pegawai/' + data.id;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Close modal by clicking overlay
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
