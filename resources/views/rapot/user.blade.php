@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center justify-center">
            <span class="text-4xl mr-3">📘</span> Rapot Saya
        </h1>
        <p class="text-gray-500 mt-1">Rekap kerja berdasarkan periode penilaian</p>
    </div>

    @if($rapots->isEmpty())
        <div class="bg-white p-10 rounded-2xl shadow text-center border border-gray-200">
            <div class="text-5xl mb-3 text-gray-400">📄</div>
            <p class="text-gray-600 text-lg">Belum ada rapot untuk ditampilkan.</p>
        </div>
    @else

        <!-- Rapot Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach($rapots as $rapot)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">

                <!-- Header Kartu -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Periode</h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Penilaian kerja periode terkait
                        </p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm">
                        {{ $rapot->periode }}
                    </span>
                </div>

                <!-- Isi Rapot -->
                <div class="space-y-4">

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Total Jam Kerja</span>
                        <span class="text-gray-900 font-bold text-lg">
                            {{ number_format($rapot->nilai, 2) }} jam
                        </span>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">Catatan</h3>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1 leading-relaxed">
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
