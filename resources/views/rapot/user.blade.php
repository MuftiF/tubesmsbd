@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10">
    
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
        📘 <span class="ml-3">Rapot Saya</span>
    </h1>

    @if($rapots->isEmpty())
        <div class="bg-white p-6 rounded-xl shadow text-center">
            <p class="text-gray-600">Belum ada rapot untuk ditampilkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach($rapots as $rapot)
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition duration-200">

                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-bold text-gray-800">Periode</h2>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $rapot->periode }}
                    </span>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Jam Kerja</span>
                        <span class="font-bold text-gray-800">
                            {{ number_format($rapot->nilai, 2) }} jam
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-gray-700 text-sm">Catatan</h3>
                        <p class="text-gray-600 mt-1">
                            {{ $rapot->catatan }}
                        </p>
                    </div>
                </div>

            </div>
            @endforeach

        </div>
    @endif

</div>
@endsection
