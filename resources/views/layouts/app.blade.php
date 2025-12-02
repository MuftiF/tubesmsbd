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
    <nav class="bg-white shadow fixed w-full z-50">
        <div class="container mx-auto flex justify-between items-center p-4">
            <!-- LOGO -->
            <div class="text-lg font-bold text-gray-700">PT. SIPIROK INDAH</div>

            <!-- HAMBURGER BUTTON -->
            <div class="md:hidden">
                <button id="menu-btn" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- NAV LINKS DESKTOP -->
            <div class="hidden md:flex space-x-4 items-center">
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Admin</a>
                        <a href="{{ route('admin.pegawai') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pegawai</a>
                        <a href="{{ route('admin.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">Laporan</a>
                        <a href="{{ route('admin.rapot.index') }}" class="text-gray-600 font-semibold hover:text-blue-600">Rapot</a>
                        <a href="{{ route('admin.pengumuman') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                    @elseif(Auth::user()->role == 'manager')
                        <a href="{{ route('manager.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Manager</a>
                        <a href="{{ route('manager.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">Laporan</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                    @elseif(Auth::user()->role == 'user')
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pekerja</a>
                        <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                    @endif
                @endauth
            </div>

            <!-- USER INFO DESKTOP -->
            <div class="hidden md:flex items-center space-x-4">
                <span class="text-sm text-gray-600">
                    {{ Auth::user()->name }}
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs ml-2">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- MOBILE MENU -->
        <div id="mobile-menu" class="fixed top-0 right-[-100%] h-full w-64 bg-white shadow-lg p-6 flex flex-col space-y-4 transition-all duration-300 z-40">
            <div class="flex justify-between items-center mb-6">
                <span class="font-bold text-gray-700 text-lg">Menu</span>
                <button id="close-btn" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @auth
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Admin</a>
                    <a href="{{ route('admin.pegawai') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pegawai</a>
                    <a href="{{ route('admin.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">Laporan</a>
                    <a href="{{ route('admin.rapot.index') }}" class="text-gray-600 font-semibold hover:text-blue-600">Rapot</a>
                    <a href="{{ route('admin.pengumuman') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                @elseif(Auth::user()->role == 'manager')
                    <a href="{{ route('manager.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Manager</a>
                    <a href="{{ route('manager.laporan') }}" class="text-gray-600 font-semibold hover:text-blue-600">Laporan</a>
                    <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                @elseif(Auth::user()->role == 'user')
                    <a href="{{ route('user.dashboard') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pekerja</a>
                    <a href="{{ route('attendance.history') }}" class="text-gray-600 font-semibold hover:text-blue-600">Riwayat</a>
                    <a href="{{ route('rapot.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Rapot</a>
                    <a href="{{ route('pengumuman.user') }}" class="text-gray-600 font-semibold hover:text-blue-600">Pengumuman</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold mt-4">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow container mx-auto mt-20 px-4">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white text-center py-4 shadow-inner text-gray-500 text-sm mt-auto">
        &copy; {{ date('Y') }} Sistem Absensi Perusahaan Sawit
    </footer>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.style.right = "0";
    });

    closeBtn.addEventListener('click', () => {
        mobileMenu.style.right = "-100%";
    });
</script>

</body>
</html>
