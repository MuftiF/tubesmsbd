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

    <!-- PESAN SUKSES (TAMPIL SETELAH UPLOAD) -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 shadow" id="successMessage">
        <div class="flex items-center">
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-1-11.414l-3.293-3.293-1.414 1.414L9 10.414l6.707-6.707-1.414-1.414L9 10.586z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
                @if(session('photos_count'))
                <p class="text-sm mt-1">
                    📸 {{ session('photos_count') }} foto telah diunggah
                    @if(session('photos_count') == 1)
                        ({{ session('photo_names')[0] }})
                    @else
                        <span class="block mt-1 text-xs">
                            @foreach(session('photo_names') as $photo)
                                • {{ $photo }}<br>
                            @endforeach
                        </span>
                    @endif
                </p>
                @endif
            </div>
            <div class="ml-auto">
                <button type="button" onclick="document.getElementById('successMessage').remove()">
                    <span class="text-green-700 text-xl">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ACTION BUTTONS -->
    <div class="space-y-4">

        @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
        <!-- BELUM CHECK IN -->

        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-xl p-6" id="uploadForm">
            @csrf

            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Dokumentasi Lapangan</h3>

            <!-- MULTIPLE FOTO -->
            <div class="mb-6">
                <label class="font-semibold text-gray-700 block mb-2">Upload Foto</label>
                <p class="text-sm text-gray-500 mb-4">Boleh satu, boleh lebih. Maksimal 5 foto. Ukuran maksimal 2MB per foto.</p>
                
                <!-- PREVIEW FOTO -->
                <div class="grid grid-cols-3 gap-2 mb-4" id="photoPreview">
                    <!-- Foto akan muncul di sini -->
                </div>

                <!-- INPUT FILE DENGAN STYLING -->
                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors duration-200" 
                     onclick="document.getElementById('photosInput').click()">
                    <p class="text-gray-600 font-medium">Klik untuk memilih foto</p>
                    <p class="text-sm text-gray-500 mt-1">atau drag & drop</p>
                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, JPEG</p>
                </div>

                <input type="file" 
                       id="photosInput"
                       name="photos[]" 
                       multiple 
                       accept="image/*"
                       class="hidden"
                       onchange="previewPhotos(event)">
                
                <!-- COUNTER -->
                <div class="text-right mt-2">
                    <span id="fileCounter" class="text-sm text-gray-500">0 foto terpilih</span>
                </div>

                @error('photos') 
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p> 
                @enderror
                @error('photos.*') 
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p> 
                @enderror
            </div>

            <!-- LOADING INDICATOR -->
            <div id="loading" class="hidden mb-4">
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-bounce"></div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
                <p class="text-center text-blue-600 mt-2">Mengunggah foto...</p>
            </div>

            <button type="submit"
                id="submitBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 flex items-center justify-center">
                <span class="text-xl mr-2">📤</span>
                Kirim Foto
            </button>
        </form>

        @endif

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

<script>
// Fungsi untuk preview foto (form pertama)
function previewPhotos(event) {
    const preview = document.getElementById('photoPreview');
    const counter = document.getElementById('fileCounter');
    const files = event.target.files;
    
    // Hapus preview sebelumnya
    preview.innerHTML = '';
    
    // Batasi maksimal 5 foto
    const maxFiles = 5;
    const selectedFiles = files.length > maxFiles ? Array.from(files).slice(0, maxFiles) : files;
    
    if (files.length > maxFiles) {
        alert(`Maksimal ${maxFiles} foto yang dapat diunggah. Hanya ${maxFiles} foto pertama yang akan diproses.`);
    }
    
    // Update counter
    counter.textContent = `${selectedFiles.length} foto terpilih`;
    
    // Buat preview untuk setiap file
    Array.from(selectedFiles).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'relative';
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-24 object-cover rounded-lg border border-gray-200"
                     alt="Preview ${index + 1}">
                <button type="button" 
                        onclick="removePhoto(${index})"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                    ×
                </button>
            `;
            preview.appendChild(previewItem);
        }
        reader.readAsDataURL(file);
    });
}

// Fungsi untuk preview foto (form kedua)
function previewPhotos2(event) {
    const preview = document.getElementById('photoPreview2');
    const counter = document.getElementById('fileCounter2');
    const files = event.target.files;
    
    // Hapus preview sebelumnya
    preview.innerHTML = '';
    
    // Batasi maksimal 5 foto
    const maxFiles = 5;
    const selectedFiles = files.length > maxFiles ? Array.from(files).slice(0, maxFiles) : files;
    
    if (files.length > maxFiles) {
        alert(`Maksimal ${maxFiles} foto yang dapat diunggah. Hanya ${maxFiles} foto pertama yang akan diproses.`);
    }
    
    // Update counter
    counter.textContent = `${selectedFiles.length} foto terpilih`;
    
    // Buat preview untuk setiap file
    Array.from(selectedFiles).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'relative';
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-24 object-cover rounded-lg border border-gray-200"
                     alt="Preview ${index + 1}">
                <button type="button" 
                        onclick="removePhoto2(${index})"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                    ×
                </button>
            `;
            preview.appendChild(previewItem);
        }
        reader.readAsDataURL(file);
    });
}

// Fungsi untuk menghapus foto dari preview
function removePhoto(index) {
    const input = document.getElementById('photosInput');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.splice(index, 1);
    files.forEach(file => dt.items.add(file));
    
    input.files = dt.files;
    previewPhotos({ target: input });
}

function removePhoto2(index) {
    const input = document.getElementById('photosInput2');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.splice(index, 1);
    files.forEach(file => dt.items.add(file));
    
    input.files = dt.files;
    previewPhotos2({ target: input });
}

// Fungsi untuk menampilkan loading saat submit
document.addEventListener('DOMContentLoaded', function() {
    const form1 = document.getElementById('uploadForm');
    const form2 = document.getElementById('uploadForm2');
    
    if (form1) {
        form1.addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<span class="text-xl mr-2">⏳</span>Mengunggah...';
        });
    }
    
    if (form2) {
        form2.addEventListener('submit', function() {
            document.getElementById('loading2').classList.remove('hidden');
            document.getElementById('submitBtn2').disabled = true;
            document.getElementById('submitBtn2').innerHTML = '<span class="text-xl mr-2">⏳</span>Mengunggah...';
        });
    }
    
    // Auto-hide success message setelah 5 detik
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 5000);
    }
});
</script>

<style>
/* Animasi untuk preview */
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.animate-bounce {
    animation: bounce 0.5s ease-in-out infinite;
}

/* Transisi untuk pesan sukses */
#successMessage {
    transition: opacity 0.3s ease;
}
</style>
@endsection