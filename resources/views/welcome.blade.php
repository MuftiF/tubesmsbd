<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Gate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-b from-gray-100 to-gray-200 flex items-center justify-center min-h-screen">

    <!-- Kartu utama -->
    <div class="bg-white p-10 rounded-2xl shadow-lg border border-gray-200 text-center w-full max-w-md">
        
        <!-- Logo besar -->
        <div class="mb-8">
            <img src="{{ asset('images/LOGO.jpg') }}" 
                 alt="Logo Perusahaan"
                 class="mx-auto w-36 h-36 rounded-xl shadow-sm border border-gray-100">
        </div>

        <!-- Teks sambutan -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h1>
        <p class="text-gray-600 mb-8">Portal resmi untuk karyawan dan pengunjung</p>

        <!-- Tombol navigasi -->
        <div class="flex justify-center gap-4">
            <a href="{{ url('/home') }}"
               class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 transition text-lg font-medium">
               Homepage
            </a>

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:ring-4 focus:ring-green-300 transition text-lg font-medium">
               Login
            </a>
        </div>

        <!-- Footer kecil -->
        <p class="text-sm text-gray-400 mt-8">© {{ date('Y') }} PT. Sipirok Indah</p>
    </div>

</body>
</html>
