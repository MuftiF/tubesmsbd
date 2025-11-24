@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    {{-- HEADER --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-extrabold text-gray-800">Kelola Pengumuman</h1>
        <p class="text-gray-500">Tambahkan atau kelola pengumuman untuk seluruh pegawai</p>
    </div>

    {{-- FORM TAMBAH --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Tambah Pengumuman</h2>

        <form method="POST" action="{{ route('admin.pengumuman.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
                <input type="text" name="judul"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                       placeholder="Masukkan judul pengumuman" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Pengumuman</label>
                <textarea name="isi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                          placeholder="Tulis isi pengumuman"
                          required></textarea>
            </div>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Tambah Pengumuman
            </button>
        </form>
    </div>

    {{-- LIST PENGUMUMAN --}}
    <div class="space-y-4">
        @forelse($announcements as $a)
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">

                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $a->judul }}
                    </h3>
                    <form action="{{ route('admin.pengumuman.delete', $a->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="text-red-600 hover:text-red-800 font-semibold text-sm bg-red-100 px-3 py-1 rounded-lg">
                            Hapus
                        </button>
                    </form>
                </div>

                <p class="text-gray-700 leading-relaxed">
                    {{ $a->isi }}
                </p>

                <div class="text-xs text-gray-400 mt-3">
                    Dibuat pada: {{ $a->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                <div class="text-4xl mb-3">📭</div>
                Belum ada pengumuman.
            </div>
        @endforelse
    </div>

</div>
@endsection
