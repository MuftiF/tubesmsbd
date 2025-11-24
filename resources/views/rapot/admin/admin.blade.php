@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10">

    <!-- HEADER -->
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-gray-800 flex items-center">
            <span class="mr-3">📘</span> Generate Rapot Pekerja
        </h1>
        <p class="text-gray-600 mt-1">Pilih pekerja dan hasilkan rapot kerja secara otomatis.</p>
    </div>

    <!-- CARD WRAPPER -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-200 overflow-hidden">

        <!-- Table -->
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Pegawai</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 w-36">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">

                    <!-- NAMA -->
                    <td class="px-6 py-4 text-gray-800 font-medium">
                        {{ $user->name }}
                    </td>

                    <!-- ROLE -->
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($user->role == 'user') bg-green-100 text-green-700
                            @elseif($user->role == 'security') bg-red-100 text-red-700
                            @elseif($user->role == 'cleaning') bg-yellow-100 text-yellow-700
                            @elseif($user->role == 'kantoran') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif
                        ">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <!-- GENERATE BUTTON -->
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.rapot.generate', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow transition active:scale-95">
                                📄 <span>Generate</span>
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>

    </div> <!-- END CARD -->
</div>
@endsection
