@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Pengumuman</h1>

    @foreach($announcements as $a)
    <div class="bg-white p-4 rounded shadow mb-3">
        <h3 class="font-bold text-lg">{{ $a->judul }}</h3>
        <p class="text-gray-700">{{ $a->isi }}</p>
        <p class="text-xs text-gray-500 mt-2">Dibuat {{ $a->created_at->diffForHumans() }}</p>
    </div>
    @endforeach

</div>
@endsection
