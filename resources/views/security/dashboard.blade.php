@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
            <span class="text-4xl">🛡️</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-wide">SECURITY DASHBOARD</h1>
        <p class="text-gray-600">Pos Keamanan & Monitoring Area</p>
    </div>

    <!-- STATUS CARD -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-l-4 border-blue-600">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Shift & Patroli</h2>

        <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">⏰</span>
                </div>
                <p class="text-sm text-gray-600">Shift Masuk</p>
                <p class="font-bold text-gray-800">18:00</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">🚶‍♂️</span>
                </div>
                <p class="text-sm text-gray-600">Patroli Ke</p>
                <p class="font-bold text-gray-800">3</p>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="space-y-4">

        @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
        <!-- BELUM CHECK IN -->

        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-xl p-6">
            @csrf

            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Dokumentasi Lapangan</h3>

            <!-- MULTIPLE FOTO -->
            <label class="font-semibold text-gray-700">Upload Foto</label>
            <p class="text-sm text-gray-500 mb-2">Boleh satu, boleh lebih. Tidak wajib banyak.</p>

            <input type="file" 
                   name="photos[]" 
                   multiple 
                   accept="image/*"
                   class="w-full mb-4 border border-gray-300 rounded-lg p-2">

            @error('photos') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            @error('photos.*') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 flex items-center justify-center">
                <span class="text-xl mr-2"></span>
                ABSEN MASUK
            </button>
        </form>

        @endif


        @if(isset($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
        <!-- SUDAH CHECK IN BELUM CHECK OUT -->

        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-xl p-6">
            @csrf

            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Dokumentasi Lapangan</h3>

                        <!-- MULTIPLE FOTO -->
            <label class="font-semibold text-gray-700">Upload Foto</label>
            <p class="text-sm text-gray-500 mb-2">Boleh satu, boleh lebih. Tidak wajib banyak.</p>

            <input type="file" 
                name="photos[]" 
                multiple 
                accept="image/*"
                class="w-full mb-4 border border-gray-300 rounded-lg p-2">

            @error('photos') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            @error('photos.*') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror


            <!-- DESKRIPSI -->
            <div class="mt-4">
                <label class="font-semibold text-gray-700">Deskripsi (Opsional)</label>
                <textarea 
                    name="description"
                    rows="3"
                    class="w-full border border-gray-300 rounded-lg p-3 mt-1 focus:ring focus:ring-blue-300"
                    placeholder="Contoh: Lokasi patroli, kondisi area, atau catatan tambahan..."
                ></textarea>

                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-xl transition duration-200 transform hover:scale-105 flex items-center justify-center">
                <span class="text-xl mr-2"></span>
                UPLOAD FOTO
            </button>
        </form>

        @endif


        @if(isset($absenHariIni) && $absenHariIni->check_out)
        <!-- SUDAH SELESAI -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow">
            <div class="text-5xl mb-2"></div>
            <h3 class="text-xl font-bold text-green-800">Absensi Selesai</h3>
            <p class="text-green-600">Terima kasih atas dedikasi Anda hari ini!</p>
            <a href="{{ route('attendance.history') }}"
               class="text-blue-600 hover:text-blue-800 font-semibold mt-3 inline-block">
                Lihat Riwayat →
            </a>
        </div>
        @endif

    </div>

    <!-- QUICK ACTIONS -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mt-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('attendance.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-center font-semibold transition transform hover:scale-105">
                <div class="text-xl mb-1">📍</div>
                <p class="text-sm">Absen</p>
            </a>

            <a href="{{ route('attendance.history') }}"
               class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-center font-semibold transition transform hover:scale-105">
                <div class="text-xl mb-1">📋</div>
                <p class="text-sm">Riwayat</p>
            </a>
        </div>
    </div>

</div>
@endsection
