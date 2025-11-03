<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Navbar (opsional) --}}
    <nav class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold text-gray-700">Absensi Karyawan</h1>
            <div>
                <a href="{{ route('attendance.index') }}" class="text-blue-600 font-semibold mx-2">Hari Ini</a>
                <a href="{{ route('attendance.history') }}" class="text-gray-600 mx-2">Riwayat</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 font-semibold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-grow container mx-auto mt-8 px-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white text-center py-4 shadow-inner text-gray-500 text-sm mt-auto">
        &copy; {{ date('Y') }} Sistem Absensi Karyawan
    </footer>

</body>
</html>
