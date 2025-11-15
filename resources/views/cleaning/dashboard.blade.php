@php
    $sudahHadir = $sudahHadir ?? false;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cleaning Service Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Tampilkan pesan sukses/error --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi Status Absen --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-center">
                    <h3 class="text-lg font-semibold mb-2">Status Absen Hari Ini</h3>
                    <p class="text-sm">
                        @if($sudahHadir)
                            <span class="text-green-600 font-medium text-lg">✓ Anda sudah absen datang hari ini</span>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Silakan lakukan absen pulang setelah selesai kerja</p>
                        @else
                            <span class="text-yellow-600 font-medium text-lg">● Belum absen datang</span>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Silakan lakukan absen datang untuk memulai kerja</p>
                        @endif
                    </p>
                </div>
            </div>

            {{-- TOMBOL HADIR DAN PULANG --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- TOMBOL HADIR --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Absen Datang</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Klik tombol di bawah untuk melakukan absen datang
                        </p>

                        @if(!$sudahHadir)
                            <button type="button" onclick="openHadirModal()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow text-lg">
                                HADIR
                            </button>
                        @else
                            <button disabled
                                class="w-full bg-gray-400 text-white font-bold py-4 px-6 rounded-lg cursor-not-allowed text-lg">
                                SUDAH HADIR
                            </button>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL PULANG --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Absen Pulang</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Klik tombol di bawah untuk melakukan absen pulang
                        </p>

                        @if($sudahHadir)
                            <button type="button" onclick="openPulangModal()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow text-lg">
                                PULANG
                            </button>
                        @else
                            <button disabled
                                class="w-full bg-gray-400 text-white font-bold py-4 px-6 rounded-lg cursor-not-allowed text-lg">
                                BELUM HADIR
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN HADIR --}}
    <div id="hadirModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Absen Datang</h3>
                
                <form action="{{ route('cleaning.absen.datang') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">
                            Foto Kehadiran
                        </label>
                        <input type="file" name="foto_datang" accept="image/*" required
                            class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900">
                        <p class="text-xs text-gray-500 mt-1">Ambil foto selfie sebagai bukti kehadiran</p>
                        @error('foto_datang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-3">
                        <button type="button" onclick="closeHadirModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN PULANG --}}
    <div id="pulangModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Absen Pulang</h3>
                
                <form action="{{ route('cleaning.absen.pulang') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">
                            Hasil Kerja Hari Ini
                        </label>
                        <textarea name="hasil_kerja" rows="3" placeholder="Tuliskan pekerjaan yang sudah diselesaikan hari ini..."
                            class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900"
                            required>{{ old('hasil_kerja') }}</textarea>
                        @error('hasil_kerja')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">
                                Foto Sebelum
                            </label>
                            <input type="file" name="foto_sebelum" accept="image/*" required
                                class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900">
                            <p class="text-xs text-gray-500 mt-1">Foto area sebelum dibersihkan</p>
                            @error('foto_sebelum')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">
                                Foto Sesudah
                            </label>
                            <input type="file" name="foto_sesudah" accept="image/*" required
                                class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900">
                            <p class="text-xs text-gray-500 mt-1">Foto area setelah dibersihkan</p>
                            @error('foto_sesudah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 pt-3">
                        <button type="button" onclick="closePulangModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openHadirModal() {
            document.getElementById('hadirModal').classList.remove('hidden');
        }

        function closeHadirModal() {
            document.getElementById('hadirModal').classList.add('hidden');
        }

        function openPulangModal() {
            document.getElementById('pulangModal').classList.remove('hidden');
        }

        function closePulangModal() {
            document.getElementById('pulangModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const hadirModal = document.getElementById('hadirModal');
            const pulangModal = document.getElementById('pulangModal');
            
            if (event.target === hadirModal) {
                closeHadirModal();
            }
            if (event.target === pulangModal) {
                closePulangModal();
            }
        }
    </script>
</x-app-layout> 