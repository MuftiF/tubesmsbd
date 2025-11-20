@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">DATA PEGAWAI</h1>
        <p class="text-gray-600">Daftar semua pegawai perusahaan</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border-l-4 border-blue-500">
            <div class="text-2xl font-bold text-blue-600">{{ $pegawai->count() }}</div>
            <div class="text-sm text-gray-600">Total Pegawai</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border-l-4 border-green-500">
            <div class="text-2xl font-bold text-green-600">{{ $pegawai->where('role', 'user')->count() }}</div>
            <div class="text-sm text-gray-600">Kebun & Panen</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border-l-4 border-purple-500">
            <div class="text-2xl font-bold text-purple-600">{{ $pegawai->where('role', 'security')->count() }}</div>
            <div class="text-sm text-gray-600">Security</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border-l-4 border-yellow-500">
            <div class="text-2xl font-bold text-yellow-600">{{ $pegawai->where('role', 'cleaning')->count() }}</div>
            <div class="text-sm text-gray-600">Cleaning</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border-l-4 border-red-500">
            <div class="text-2xl font-bold text-red-600">{{ $pegawai->where('role', 'kantoran')->count() }}</div>
            <div class="text-sm text-gray-600">Administrasi</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari pegawai..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select id="roleFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user">Kebun & Panen</option>
                    <option value="security">Security</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="kantoran">Administrasi</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pegawai</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="employeeTable">
                    @foreach($pegawai as $index => $emp)
                    <tr class="hover:bg-gray-50 employee-row" data-role="{{ $emp->role }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($emp->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $emp->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $emp->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($emp->role == 'admin') bg-red-100 text-red-800
                                @elseif($emp->role == 'manager') bg-purple-100 text-purple-800
                                @elseif($emp->role == 'user') bg-green-100 text-green-800
                                @elseif($emp->role == 'security') bg-blue-100 text-blue-800
                                @elseif($emp->role == 'cleaning') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($emp->role == 'user') Kebun & Panen
                                @elseif($emp->role == 'security') Security
                                @elseif($emp->role == 'cleaning') Cleaning
                                @elseif($emp->role == 'kantoran') Administrasi
                                @else {{ ucfirst($emp->role) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $emp->created_at ? $emp->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pegawai->isEmpty())
        <div class="px-6 py-12 text-center">
            <div class="text-gray-500">
                <div class="text-4xl mb-4">👥</div>
                <div>Tidak ada data pegawai</div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const employeeRows = document.querySelectorAll('.employee-row');

    function filterEmployees() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;

        employeeRows.forEach(row => {
            const name = row.querySelector('.text-gray-900').textContent.toLowerCase();
            const email = row.querySelector('.text-gray-500').textContent.toLowerCase();
            const role = row.dataset.role;

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = !selectedRole || role === selectedRole;

            if (matchesSearch && matchesRole) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterEmployees);
    roleFilter.addEventListener('change', filterEmployees);
});
</script>
@endsection
