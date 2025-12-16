@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-6 px-4">
    <!-- HEADER SIMPLE -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Evaluasi Kinerja</h1>
        <p class="text-gray-600 mt-1">Kelola evaluasi kinerja pegawai</p>
    </div>

    <!-- SINGLE COLUMN LAYOUT -->
    <div class="grid grid-cols-1 gap-6">
        <!-- CARD: DAFTAR PEGAWAI -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Pegawai</h2>
                    <span class="text-sm text-gray-500">{{ $users->count() }} pegawai</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm text-gray-600">
                            <th class="px-5 py-3 font-medium">Nama</th>
                            <th class="px-5 py-3 font-medium">Posisi</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <!-- NAMA -->
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->no_hp ?? '-' }}</div>
                            </td>

                            <!-- ROLE -->
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($user->role == 'user') bg-green-100 text-green-800
                                    @elseif($user->role == 'security') bg-red-100 text-red-800
                                    @elseif($user->role == 'cleaning') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="px-5 py-4 text-sm">
                                @if($user->last_evaluated_at)
                                    <div class="text-gray-700">
                                        {{ \Carbon\Carbon::parse($user->last_evaluated_at)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($user->last_evaluated_at)->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-gray-400">Belum dinilai</span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="px-5 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rapot.evaluasi.create', $user->id) }}"
                                       class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition">
                                        Evaluasi
                                    </a>
                                    <form action="{{ route('admin.rapot.generate', $user->id) }}" method="POST">
                                        @csrf
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                Tidak ada data pegawai
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- CARD: RIWAYAT TERBARU -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Riwayat Evaluasi Terbaru</h2>
                <p class="text-sm text-gray-500">{{ $rapots->count() }} hasil terbaru</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($rapots as $rapot)
                <div class="px-5 py-4 hover:bg-gray-50">
                    <div class="flex justify-between">
                        <div>
                            <div class="font-medium text-gray-900">{{ $rapot->user->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $rapot->periode }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <!-- HAPUS BAGIAN NILAI/RATING -->
                                <span class="text-xs px-2 py-0.5 bg-gray-100 rounded">
                                    Status: {{ $rapot->status ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.rapot.show', $rapot->id) }}"
                               class="px-2 py-1 text-gray-500 hover:text-blue-600">
                                Lihat
                            </a>
                            <a href="{{ route('admin.rapot.export.pdf', $rapot->id) }}"
                               class="px-2 py-1 text-gray-500 hover:text-red-600">
                                PDF
                            </a>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-400 flex justify-between">
                        <span>{{ \Carbon\Carbon::parse($rapot->generated_at)->diffForHumans() }}</span>
                        <span class="font-medium">
                            {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-500">
                    Belum ada riwayat evaluasi
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple toast notification
    @if(session('success'))
    setTimeout(() => {
        alert('{{ session('success') }}');
    }, 100);
    @endif

    @if(session('error'))
    setTimeout(() => {
        alert('Error: {{ session('error') }}');
    }, 100);
    @endif
</script>
@endpush