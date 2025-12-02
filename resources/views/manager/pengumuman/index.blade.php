@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pengumuman (Manager)</h1>
        <p class="text-gray-500 text-sm">Tambah, lihat, dan kelola pengumuman perusahaan.</p>
    </div>

    <!-- Alert Sukses/Error -->
    @if(session('success'))
        <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Tambah -->
    <div class="bg-white shadow-md border border-gray-200 rounded-xl p-6 mb-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Pengumuman Baru</h2>

        <form method="POST" action="{{ route('manager.pengumuman.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input 
                    type="text" 
                    name="judul" 
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                    placeholder="Masukkan judul pengumuman">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Isi Pengumuman</label>
                <textarea 
                    name="isi" 
                    rows="4"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                    placeholder="Tulis isi pengumuman..."></textarea>
            </div>

            <button 
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm transition">
                Tambah Pengumuman
            </button>
        </form>
    </div>

    <!-- List Pengumuman -->
    <div class="space-y-4">
        @foreach($announcements as $a)
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $a->judul }}</h3>
                    <p class="text-gray-600 mt-1 leading-relaxed">{{ $a->isi }}</p>
                </div>
            </div>

            <form action="{{ route('manager.pengumuman.delete', $a->id) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                    Hapus
                </button>
            </form>
        </div>
        @endforeach
    </div>

</div>
@endsection
