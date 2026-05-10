<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan - PT. Sipirok Indah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom transition for mobile sidebar */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        /* Active menu style */
        .nav-active {
            background-color: #2d6a4f;
            color: white !important;
        }
        .nav-active svg {
            stroke: white;
        }
        .nav-item:hover {
            background-color: #f0fdf4;
        }
        /* For desktop sidebar fixed position */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16rem;
            }
        }
        /* Make footer stick to bottom */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .flex-1 {
            flex: 1;
        }
        /* Logo styling */
        .logo-image {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 8px;
        }
        .sidebar-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mobile-logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            border-radius: 6px;
        }
        @media (max-width: 768px) {
            .topbar-logo {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row">

    <!-- DESKTOP SIDEBAR (VERTICAL LEFT) -->
    <aside class="hidden md:flex md:flex-col md:w-64 bg-white shadow-lg fixed h-full z-30">
        <div class="p-6 border-b">
            <div class="sidebar-logo-container">
                <!-- Logo 1.jpg added here -->
                <img src="{{ asset('images/Logo 1.jpg') }}" alt="Logo PT Sipirok Indah" class="logo-image" onerror="this.src='https://placehold.co/40x40?text=Logo'">
                <div>
                    <div class="text-xl font-bold text-gray-800">PT. SIPIROK INDAH</div>
                    <p class="text-xs text-gray-500 mt-1">Sistem Absensi</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @auth
                @switch(Auth::user()->role)
                    @case('admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.pegawai') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('admin.pegawai') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Pegawai</span>
                        </a>
                        <!-- Menu Log Absensi untuk Admin telah dihapus -->
                        <a href="{{ route('admin.laporan') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('admin.laporan') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Laporan</span>
                        </a>
                        <a href="{{ route('admin.rapot.index') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('admin.rapot.*') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rapot</span>
                        </a>
                        <a href="{{ route('admin.pengumuman') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('admin.pengumuman') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break
                        
                    @case('manager')
                        <a href="{{ route('manager.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('manager.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <!-- Menu Log Absensi untuk Manager telah dihapus -->
                        <a href="{{ route('manager.laporan') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('manager.laporan') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Laporan</span>
                        </a>
                        <a href="{{ route('manager.pegawai') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('manager.pegawai') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Kelola Pegawai</span>
                        </a>
                        <a href="{{ route('pengumuman.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break
                        
                    @case('user')
                        <a href="{{ route('user.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('user.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('attendance.history') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Riwayat Absen</span>
                        </a>
                        <a href="{{ route('rapot.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rapot Saya</span>
                        </a>
                        <a href="{{ route('pengumuman.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break
                        
                    @case('security')
                        <a href="{{ route('security.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('security.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('attendance.history') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Riwayat Absen</span>
                        </a>
                        <a href="{{ route('rapot.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rapot</span>
                        </a>
                        <a href="{{ route('pengumuman.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break

                    @case('cleaning')
                        <a href="{{ route('cleaning.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('cleaning.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('attendance.history') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Riwayat Absen</span>
                        </a>
                        <a href="{{ route('rapot.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rapot</span>
                        </a>
                        <a href="{{ route('pengumuman.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break

                    @case('kantoran')
                        <a href="{{ route('kantoran.dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('kantoran.dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('attendance.history') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Riwayat Absen</span>
                        </a>
                        <a href="{{ route('rapot.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rapot</span>
                        </a>
                        <a href="{{ route('pengumuman.user') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            <span>Pengumuman</span>
                        </a>
                        @break
                        
                    @default
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                @endswitch
            @endauth
        </nav>
    </aside>

    <!-- MOBILE HAMBURGER BUTTON (only visible on small screens) -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="menu-btn-mobile" class="text-gray-600 focus:outline-none bg-white p-2 rounded-lg shadow">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- MOBILE SIDEBAR (off-canvas) dengan Logo -->
    <div id="mobile-sidebar" class="fixed top-0 left-[-100%] h-full w-64 bg-white shadow-lg p-6 flex flex-col z-50 sidebar-transition md:hidden">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div class="mobile-logo-container">
                <img src="{{ asset('images/Logo 1.jpg') }}" alt="Logo PT Sipirok Indah" class="logo-image" onerror="this.src='https://placehold.co/40x40?text=Logo'">
                <div>
                    <div class="font-bold text-gray-800">PT. SIPIROK INDAH</div>
                    <div class="text-xs text-gray-500">Sistem Absensi</div>
                </div>
            </div>
            <button id="close-btn-mobile" class="text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto">
            @auth
                @switch(Auth::user()->role)
                    @case('admin')
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.pegawai') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('admin.pegawai') ? 'nav-active' : '' }}">Pegawai</a>
                        <!-- Menu Log Absensi untuk Admin (Mobile) telah dihapus -->
                        <a href="{{ route('admin.laporan') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('admin.laporan') ? 'nav-active' : '' }}">Laporan</a>
                        <a href="{{ route('admin.rapot.index') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('admin.rapot.*') ? 'nav-active' : '' }}">Rapot</a>
                        <a href="{{ route('admin.pengumuman') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('admin.pengumuman') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                        
                    @case('manager')
                        <a href="{{ route('manager.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('manager.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <!-- Menu Log Absensi untuk Manager (Mobile) telah dihapus -->
                        <a href="{{ route('manager.laporan') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('manager.laporan') ? 'nav-active' : '' }}">Laporan</a>
                        <a href="{{ route('manager.pegawai') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('manager.pegawai') ? 'nav-active' : '' }}">Kelola Pegawai</a>
                        <a href="{{ route('pengumuman.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                        
                    @case('user')
                        <a href="{{ route('user.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('user.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <a href="{{ route('attendance.history') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">Riwayat Absen</a>
                        <a href="{{ route('rapot.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">Rapot Saya</a>
                        <a href="{{ route('pengumuman.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                        
                    @case('security')
                        <a href="{{ route('security.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('security.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <a href="{{ route('attendance.history') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">Riwayat Absen</a>
                        <a href="{{ route('rapot.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                        
                    @case('cleaning')
                        <a href="{{ route('cleaning.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('cleaning.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <a href="{{ route('attendance.history') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">Riwayat Absen</a>
                        <a href="{{ route('rapot.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                        
                    @case('kantoran')
                        <a href="{{ route('kantoran.dashboard') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('kantoran.dashboard') ? 'nav-active' : '' }}">Dashboard</a>
                        <a href="{{ route('attendance.history') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('attendance.history') ? 'nav-active' : '' }}">Riwayat Absen</a>
                        <a href="{{ route('rapot.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('rapot.user') ? 'nav-active' : '' }}">Rapot</a>
                        <a href="{{ route('pengumuman.user') }}" class="mobile-nav-item block px-4 py-3 rounded-lg text-gray-700 font-medium {{ request()->routeIs('pengumuman.user') ? 'nav-active' : '' }}">Pengumuman</a>
                        @break
                @endswitch
            @endauth
        </nav>
        
        <!-- Mobile Logout Button -->
        <div class="pt-4 mt-4 border-t">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">Logout</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 main-content md:ml-64 flex flex-col min-h-screen">
        <!-- TOP BAR with Profile & Logout (Logo ditambahkan di sebelah kiri user info) -->
        <div class="bg-white shadow-sm sticky top-0 z-20">
            <div class="flex justify-end items-center px-6 py-3">
                <div class="flex items-center space-x-4">
                    <!-- Logo tambahan di top bar (untuk tampilan layar besar, bisa opsional) -->

                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-500">
                            @auth
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ ucfirst(Auth::user()->role) }}</span>
                            @endauth
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT - This will grow to push footer down -->
        <main class="flex-1 container mx-auto px-4 py-6">
            @yield('content')
        </main>

        <!-- FOOTER - Always at bottom -->
        <footer class="bg-white text-center py-4 shadow-inner text-gray-500 text-sm">
            &copy; {{ date('Y') }} Sistem Absensi Perusahaan Sawit - PT. Sipirok Indah
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('menu-btn-mobile');
        const closeBtn = document.getElementById('close-btn-mobile');
        const mobileSidebar = document.getElementById('mobile-sidebar');

        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileSidebar.style.left = "0";
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                mobileSidebar.style.left = "-100%";
            });
        }

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.mobile-nav-item').forEach(link => {
            link.addEventListener('click', () => {
                mobileSidebar.style.left = "-100%";
            });
        });
        
        // Close sidebar when clicking outside on mobile (optional)
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768) {
                const isClickInside = mobileSidebar.contains(event.target);
                const isClickOnMenuBtn = menuBtn && menuBtn.contains(event.target);
                
                if (!isClickInside && !isClickOnMenuBtn && mobileSidebar.style.left === "0px") {
                    mobileSidebar.style.left = "-100%";
                }
            }
        });
    </script>
</body>
</html>