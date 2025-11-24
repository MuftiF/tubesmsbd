@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Pengumuman</h1>
        <p class="text-gray-500">Informasi terbaru untuk seluruh pegawai</p>
    </div>

    <!-- List Pengumuman -->
    <div class="space-y-4">
        @forelse($announcements as $a)
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition">
                <h3 class="font-bold text-xl text-gray-800 mb-2">
                    {{ $a->judul }}
                </h3>

                <p class="text-gray-700 leading-relaxed">
                    {{ $a->isi }}
                </p>

                <p class="text-xs text-gray-400 mt-3">
                    Dibuat {{ $a->created_at->diffForHumans() }}
                </p>
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
