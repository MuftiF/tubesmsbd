@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Kelola Pengumuman</h1>

    <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="bg-white p-4 rounded shadow mb-6">
        @csrf
        <label class="font-semibold">Judul:</label>
        <input type="text" name="judul" class="w-full mt-1 mb-3 p-2 border rounded">

        <label class="font-semibold">Isi Pengumuman:</label>
        <textarea name="isi" class="w-full mt-1 p-2 border rounded" rows="4"></textarea>

        <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    @foreach($announcements as $a)
        <div class="bg-white p-4 rounded shadow mb-3">
            <h3 class="font-bold text-lg">{{ $a->judul }}</h3>
            <p class="text-gray-700">{{ $a->isi }}</p>

            <form action="{{ route('admin.pengumuman.delete', $a->id) }}" method="POST" class="mt-3">
                @csrf @method('DELETE')
                <button class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
            </form>
        </div>
    @endforeach

</div>
@endsection
