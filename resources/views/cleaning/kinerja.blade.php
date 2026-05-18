@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- ============================================================ --}}
        {{-- HEADER SECTION --}}
        {{-- ============================================================ --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
</svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Cleaning Service</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Sistem Kinerja Cleaning</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs md:text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
                        <span class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-xs md:text-sm font-medium mt-1">
                            PT. Sipirok Indah
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ALERT MESSAGES --}}
        {{-- ============================================================ --}}
        @if(session('success'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#e8f5f0] border border-[#2e7d5e]/20 flex items-center gap-3" id="successMessage">
            <svg class="w-5 h-5 text-[#2e7d5e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm md:text-base text-[#1f4a3d] flex-1">{{ session('success') }}</p>
            <button type="button" onclick="document.getElementById('successMessage').remove()" class="text-[#2c5e4e]/60 hover:text-[#2c5e4e] text-xl leading-none">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#FDECEA] border border-[#C0392B]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#C0392B] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm md:text-base text-[#7B1C14]">{{ session('error') }}</p>
        </div>
        @endif

     

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION --}}
        {{-- ============================================================ --}}
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('input')" id="tab-input-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Input Kinerja
            </button>
            <button onclick="showTab('riwayat')" id="tab-riwayat-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Riwayat Hari Ini
            </button>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: INPUT KINERJA --}}
        {{-- ============================================================ --}}
        <div id="tab-input" class="tab-content">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Form Laporan Pekerjaan</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">* wajib diisi</span>
                    </div>
                </div>
                <div class="p-4 md:p-7">
                    <form action="{{ route('cleaning.kinerja.store') }}" method="POST" id="kinerjaForm">
                        @csrf

                        <div id="wrapper" class="space-y-5 sm:space-y-6">
                            {{-- ITEM PERTAMA --}}
                            <div class="item bg-[#F8FAF9] rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] relative transition-all hover:border-[#2c5e4e]/30">
                                <div class="flex items-center gap-2 mb-3 sm:mb-4 pb-2 border-b border-[#E2E8F0]">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2c5e4e] flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">1</span>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-500">Area Pekerjaan</span>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Area <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="area[]"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 mt-1 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm sm:text-base"
                                           placeholder="Contoh: Area Lobby, Toilet Lt.2, Koridor Utama"
                                           required>
                                </div>

                                <div class="mb-4 sm:mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Keterangan Pekerjaan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="keterangan[]"
                                              required
                                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 mt-1 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm sm:text-base"
                                              rows="3"
                                              placeholder="Contoh: Membersihkan lantai, mengepel, menyapu, dll."></textarea>
                                </div>

                                {{-- CAMERA --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Foto Bukti <span class="text-red-500">*</span>
                                    </label>

                                    <div class="video-container relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-md mx-auto">
                                        <div class="aspect-[4/3]">
                                            <video class="w-full h-full object-cover" autoplay playsinline muted></video>
                                            <canvas class="hidden"></canvas>
                                            <button type="button"
                                                    class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-4 py-1.5 sm:px-5 sm:py-2 rounded-full shadow-lg transition-all text-xs sm:text-sm"
                                                    onclick="takePhoto(this)">
                                                <svg class="inline w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Ambil Foto
                                            </button>
                                        </div>
                                    </div>

                                    <div class="photo-preview-container hidden mt-3">
                                        <div class="bg-[#eaf4f1] rounded-xl p-3 sm:p-4 flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                                            <img class="photo-preview w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover border-2 border-white shadow" src="" alt="Preview">
                                            <div class="flex-1 text-center sm:text-left">
                                                <p class="font-semibold text-gray-800 text-sm sm:text-base">Foto berhasil diambil</p>
                                                <p class="text-xs text-gray-500">Pastikan area kerja terlihat jelas</p>
                                                <button type="button"
                                                        class="mt-1 sm:mt-2 text-xs sm:text-sm text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1"
                                                        onclick="retakePhoto(this)">
                                                    <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Ambil Ulang
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="foto[]" />
                                </div>
                            </div>
                        </div>

                        {{-- BUTTON ACTIONS --}}
                        <div class="flex flex-wrap gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-[#E2E8F0]">
                            <button type="button"
                                    onclick="tambahForm()"
                                    class="inline-flex items-center gap-2 bg-white border border-[#2c5e4e] text-[#2c5e4e] hover:bg-[#eaf4f1] px-4 py-2.5 sm:px-5 sm:py-3 rounded-xl font-semibold transition-all text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Area
                            </button>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl font-semibold shadow-md transition-all text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Semua Kinerja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: RIWAYAT HARI INI --}}
        {{-- ============================================================ --}}
        <div id="tab-riwayat" class="tab-content hidden">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h2 class="text-base md:text-lg font-semibold text-gray-700">Riwayat Kinerja Hari Ini</h2>
                    </div>
                    <div class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1.5 md:px-4 md:py-2 rounded-xl font-semibold flex items-center gap-2 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total: {{ $riwayatHariIni->count() }}
                    </div>
                </div>
                <div class="p-4 md:p-7">
                    @if($riwayatHariIni->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            @foreach($riwayatHariIni as $item)
                                <div class="border border-[#E2E8F0] rounded-xl overflow-hidden bg-white transition-all hover:shadow-md">
                                    @php
                                        $fotoUrl = $item->foto;
                                        if (str_starts_with($fotoUrl, 'storage/')) {
                                            $fotoUrl = asset($fotoUrl);
                                        } elseif (str_starts_with($fotoUrl, '/storage')) {
                                            $fotoUrl = asset($fotoUrl);
                                        } elseif (!str_starts_with($fotoUrl, 'http')) {
                                            $fotoUrl = asset('storage/' . $fotoUrl);
                                        }
                                    @endphp
                                    <img src="{{ $fotoUrl }}" 
                                         onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Ada'"
                                         class="w-full h-48 md:h-56 object-cover bg-gray-100">
                                    <div class="p-4 md:p-5">
                                        <div class="flex justify-between items-start mb-3 flex-wrap gap-2">
                                            <div>
                                                <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center gap-2">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                    </svg>
                                                    {{ $item->area }}
                                                </h3>
                                                <p class="text-xs md:text-sm text-gray-500 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}
                                                </p>
                                            </div>
                                            <span class="bg-[#eaf4f1] text-[#2c5e4e] px-2 py-0.5 md:px-3 md:py-1 rounded-full text-xs md:text-sm font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                Cleaning
                                            </span>
                                        </div>
                                        <div class="text-gray-700 mt-3 pt-3 border-t border-[#E2E8F0]">
                                            <p class="text-xs md:text-sm">
                                                <span class="font-semibold">Keterangan:</span><br>
                                                {{ $item->keterangan ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 md:py-12">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Kinerja Hari Ini</h3>
                            <p class="text-xs md:text-sm text-gray-500 mt-1">Silakan buka tab Input Kinerja untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- INFO PANDUAN --}}
        {{-- ============================================================ --}}
        <div class="mt-5 sm:mt-6 bg-[#eaf4f1]/50 rounded-xl p-3 sm:p-4 border border-[#2c5e4e]/10">
            <div class="flex items-start gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2c5e4e] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#2c5e4e]">Panduan Pengisian</p>
                    <ul class="text-xs sm:text-sm text-gray-600 mt-1 space-y-0.5">
                        <li>• Setiap area pekerjaan harus memiliki foto bukti yang jelas</li>
                        <li>• Pastikan kamera sudah diizinkan aksesnya oleh browser</li>
                        <li>• Anda dapat menambahkan beberapa area pekerjaan sekaligus</li>
                        <li>• Klik "Ambil Foto" untuk mendokumentasikan hasil pekerjaan</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<script>

let streams = {};

/**
 * Aktifkan kamera untuk video element tertentu
 */
async function initCamera(video) {
    if (!video) return;
    
    // Hentikan stream lama jika ada
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 1024 },
                height: { ideal: 768 }
            }, 
            audio: false 
        });
        
        // Simpan stream
        let container = video.closest('.item');
        let streamId = 'stream_' + Date.now() + '_' + Math.random();
        streams[streamId] = stream;
        video.setAttribute('data-stream-id', streamId);
        video.srcObject = stream;
        
        // Tunggu video siap
        await new Promise((resolve) => {
            video.onloadedmetadata = () => {
                video.play();
                resolve();
            };
        });
    } catch (err) {
        console.error(err);
        alert("Kamera tidak bisa diakses. Periksa izin browser Anda.");
    }
}

/**
 * Hentikan kamera tertentu
 */
function stopCamera(video) {
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
        video.srcObject = null;
    }
}

/**
 * Ambil foto dari video
 */
function takePhoto(btn) {
    let container = btn.closest('.item');

    let video = container.querySelector('video');
    let canvas = container.querySelector('canvas');
    let input = container.querySelector('input[type="hidden"]');
    let previewContainer = container.querySelector('.photo-preview-container');
    let previewImg = container.querySelector('.photo-preview');
    let videoContainer = container.querySelector('.video-container');

    if (!video.videoWidth || video.videoWidth === 0) {
        alert("Kamera belum siap, tunggu sebentar.");
        return;
    }

    // Set canvas size sesuai video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    let ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Konversi ke JPEG dengan kualitas 85%
    let dataURL = canvas.toDataURL('image/jpeg', 0.85);

    // Simpan ke hidden input
    input.value = dataURL;

    // Tampilkan preview
    previewImg.src = dataURL;
    previewContainer.classList.remove('hidden');

    // Sembunyikan video container
    if (videoContainer) {
        videoContainer.classList.add('hidden');
    }

    // Hentikan kamera untuk menghemat resource
    stopCamera(video);
}

/**
 * Retake foto
 */
function retakePhoto(btn) {
    let container = btn.closest('.item');

    let img = container.querySelector('.photo-preview');
    let input = container.querySelector('input[type="hidden"]');
    let previewContainer = container.querySelector('.photo-preview-container');
    let videoContainer = container.querySelector('.video-container');
    let video = container.querySelector('video');

    // Reset nilai
    input.value = "";
    previewContainer.classList.add('hidden');

    // Tampilkan kembali video container
    if (videoContainer) {
        videoContainer.classList.remove('hidden');
    }

    // Restart kamera
    initCamera(video);
}

/**
 * Hapus form item
 */
function hapusForm(btn) {
    let container = btn.closest('.item');
    let video = container.querySelector('video');
    
    // Hentikan kamera sebelum dihapus
    if (video) {
        stopCamera(video);
    }
    
    container.remove();
    
    // Update nomor urut
    updateItemNumbers();
}

/**
 * Update nomor urut pada setiap item
 */
function updateItemNumbers() {
    let items = document.querySelectorAll('.item');
    items.forEach((item, index) => {
        let numberCircle = item.querySelector('.w-6.h-6.rounded-full.bg-\\[\\#2c5e4e\\] span, .w-7.h-7.rounded-full.bg-\\[\\#2c5e4e\\] span');
        if (numberCircle) {
            numberCircle.textContent = index + 1;
        }
    });
}

/**
 * Tambah form baru
 */
function tambahForm() {
    let wrapper = document.getElementById('wrapper');
    let currentCount = document.querySelectorAll('.item').length;
    let newNumber = currentCount + 1;

    let html = `
        <div class="item bg-[#F8FAF9] rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-[#E2E8F0] relative transition-all hover:border-[#2c5e4e]/30">
            <button type="button"
                    onclick="hapusForm(this)"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>

            <div class="flex items-center gap-2 mb-3 sm:mb-4 pb-2 border-b border-[#E2E8F0]">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#2c5e4e] flex items-center justify-center">
                    <span class="text-white text-xs font-bold">${newNumber}</span>
                </div>
                <span class="text-xs sm:text-sm font-medium text-gray-500">Area Pekerjaan</span>
            </div>

            <div class="mb-4 sm:mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Area <span class="text-red-500">*</span>
                </label>
                <input type="text" name="area[]"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 mt-1 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm sm:text-base"
                       placeholder="Contoh: Area Lobby, Toilet Lt.2, Koridor Utama"
                       required>
            </div>

            <div class="mb-4 sm:mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Keterangan Pekerjaan <span class="text-red-500">*</span>
                </label>
                <textarea name="keterangan[]"
                          required
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 sm:py-3 mt-1 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm sm:text-base"
                          rows="3"
                          placeholder="Contoh: Membersihkan lantai, mengepel, menyapu, dll."></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="inline w-4 h-4 mr-1 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Foto Bukti <span class="text-red-500">*</span>
                </label>

                <div class="video-container relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-md mx-auto">
                    <div class="aspect-[4/3]">
                        <video class="w-full h-full object-cover" autoplay playsinline muted></video>
                        <canvas class="hidden"></canvas>
                        <button type="button"
                                class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-4 py-1.5 sm:px-5 sm:py-2 rounded-full shadow-lg transition-all text-xs sm:text-sm"
                                onclick="takePhoto(this)">
                            <svg class="inline w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </button>
                    </div>
                </div>

                <div class="photo-preview-container hidden mt-3">
                    <div class="bg-[#eaf4f1] rounded-xl p-3 sm:p-4 flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                        <img class="photo-preview w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover border-2 border-white shadow" src="" alt="Preview">
                        <div class="flex-1 text-center sm:text-left">
                            <p class="font-semibold text-gray-800 text-sm sm:text-base">Foto berhasil diambil</p>
                            <p class="text-xs text-gray-500">Pastikan area kerja terlihat jelas</p>
                            <button type="button"
                                    class="mt-1 sm:mt-2 text-xs sm:text-sm text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1"
                                    onclick="retakePhoto(this)">
                                <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Ambil Ulang
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="foto[]" />
            </div>
        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    // Inisialisasi kamera untuk video baru
    let newVideo = wrapper.querySelector('.item:last-child video');
    if (newVideo) {
        initCamera(newVideo);
    }
}

// ── TABS ─────────────────────────────────────────────
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');

    const inputBtn = document.getElementById('tab-input-btn');
    const riwayatBtn = document.getElementById('tab-riwayat-btn');

    if (tab === 'input') {
        inputBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        riwayatBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        
        // Re-init cameras when switching to input tab
        setTimeout(() => {
            document.querySelectorAll('.item video').forEach(video => initCamera(video));
        }, 100);
    } else {
        riwayatBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        riwayatBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        inputBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        inputBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        
        // Stop all cameras when leaving input tab
        document.querySelectorAll('.item video').forEach(video => stopCamera(video));
    }
}

/**
 * Validasi form sebelum submit
 */
document.getElementById('kinerjaForm')?.addEventListener('submit', function(e) {
    let items = document.querySelectorAll('.item');
    let isValid = true;
    let emptyFields = [];

    items.forEach((item, index) => {
        let area = item.querySelector('input[name="area[]"]')?.value.trim();
        let keterangan = item.querySelector('textarea[name="keterangan[]"]')?.value.trim();
        let foto = item.querySelector('input[name="foto[]"]')?.value;

        if (!area) {
            isValid = false;
            emptyFields.push(`Area #${index + 1}`);
        }
        if (!keterangan) {
            isValid = false;
            emptyFields.push(`Keterangan #${index + 1}`);
        }
        if (!foto) {
            isValid = false;
            emptyFields.push(`Foto #${index + 1}`);
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert(`Data belum lengkap!\nSilakan isi: ${emptyFields.join(', ')}`);
    } else {
        let btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.innerHTML = `
                <svg class="w-4 h-4 sm:w-5 sm:h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
            btn.disabled = true;
        }
    }
});

// ── CLOCK ───────────────────────────────────────────
let currentTime = new Date("{{ $serverTime ?? now() }}");

function updateClock() {
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    const el = document.getElementById('realtimeClock');
    if (el) el.textContent = currentTime.toLocaleTimeString('id-ID', { hour12: false });
}
setInterval(updateClock, 1000);
updateClock();

/**
 * Init kamera awal untuk semua video yang sudah ada
 */
document.addEventListener("DOMContentLoaded", async function () {
    showTab('input');
    let videos = document.querySelectorAll(".item video");
    for (let video of videos) {
        await initCamera(video);
    }
});

/**
 * Cleanup streams saat halaman ditutup
 */
window.addEventListener('beforeunload', function() {
    Object.values(streams).forEach(stream => {
        if (stream && stream.getTracks) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
});

</script>

<style>
.tab-content {
    transition: all 0.3s ease;
}
#successMessage {
    transition: opacity 0.3s ease;
}
</style>
@endsection