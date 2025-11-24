<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">

            <!-- KIRI -->
            <div class="flex items-center space-x-6">
                <h1 class="text-lg font-bold text-gray-700">PT. SIPIROK INDAH</h1>

                <div class="flex space-x-4">
                    @auth

                    {{-- ADMIN --}}
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">👑 Admin</a>
                        <a href="{{ route('admin.pegawai') }}" class="text-gray-600 font-semibold hover:text-blue-600">👥 Pegawai</a>
                        <a href="{{ route('admin.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">📊 Laporan</a>
                        <a href="{{ route('admin.rapot.index') }}" class="text-gray-600 font-semibold hover:text-blue-600">📘 Rapot</a>
                        <a href="{{ route('pengumuman.admin') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>

                    {{-- MANAGER --}}
                    @elseif(Auth::user()->role == 'manager')
                        <a href="{{ route('manager.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">👨‍💼 Manager</a>
                        <a href="{{ route('manager.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">📊 Laporan</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>

                    {{-- USER --}}
                    @elseif(Auth::user()->role == 'user')
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">🌴 Pekerja</a>
                        <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📘 Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>

                    {{-- SECURITY --}}
                    @elseif(Auth::user()->role == 'security')
                        <a href="{{ route('security.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">🛡️ Security</a>
                        <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📘 Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>

                    {{-- CLEANING --}}
                    @elseif(Auth::user()->role == 'cleaning')
                        <a href="{{ route('cleaning.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">🧹 Cleaning</a>
                        <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📘 Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>

                    {{-- KANTORAN --}}
                    @elseif(Auth::user()->role == 'kantoran')
                        <a href="{{ route('kantoran.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">🏢 Kantoran</a>
                        <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📘 Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">📢 Pengumuman</a>
                    @endif

                    @endauth
                </div>
            </div>

            <!-- KANAN -->
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">
                    {{ Auth::user()->name }}
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs ml-2">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold flex items-center">
                        <span class="mr-2">🚪</span> Logout
                    </button>
                </form>
            </div>

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow container mx-auto mt-8 px-4">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white text-center py-4 shadow-inner text-gray-500 text-sm mt-auto">
        &copy; {{ date('Y') }} Sistem Absensi Perusahaan Sawit
    </footer>

</body>
</html>
