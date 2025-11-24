<nav class="bg-white shadow p-4">
    <div class="container mx-auto flex justify-between items-center">

        {{-- Kiri --}}
        <div class="flex items-center space-x-6">
            <h1 class="text-lg font-bold text-gray-700">PT. SIPIROK INDAH</h1>

            <div class="flex space-x-4">
                @auth
                    {{-- ADMIN --}}
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">👑 Admin</a>
                        <a href="{{ route('admin.pegawai') }}" class="nav-link">👥 Pegawai</a>
                        <a href="{{ route('admin.laporan') }}" class="nav-link">📊 Laporan</a>
                        <a href="{{ route('admin.rapot.index') }}" class="nav-link">📘 Rapot Admin</a>

                    {{-- MANAGER --}}
                    @elseif(Auth::user()->role == 'manager')
                        <a href="{{ route('manager.dashboard') }}" class="nav-link">👨‍💼 Manager</a>
                        <a href="{{ route('manager.laporan') }}" class="nav-link">📊 Laporan</a>

                    {{-- USER (Pemanen) --}}
                    @elseif(Auth::user()->role == 'user')
                        <a href="{{ route('user.dashboard') }}" class="nav-link">🌴 Pekerja</a>
                        <a href="{{ route('attendance.history') }}" class="nav-link">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="nav-link">📘 Rapot</a>

                    {{-- SECURITY --}}
                    @elseif(Auth::user()->role == 'security')
                        <a href="{{ route('security.dashboard') }}" class="nav-link">🛡️ Security</a>
                        <a href="{{ route('attendance.history') }}" class="nav-link">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="nav-link">📘 Rapot</a>

                    {{-- CLEANING --}}
                    @elseif(Auth::user()->role == 'cleaning')
                        <a href="{{ route('cleaning.dashboard') }}" class="nav-link">🧹 Cleaning</a>
                        <a href="{{ route('attendance.history') }}" class="nav-link">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="nav-link">📘 Rapot</a>

                    {{-- KANTORAN --}}
                    @elseif(Auth::user()->role == 'kantoran')
                        <a href="{{ route('kantoran.dashboard') }}" class="nav-link">🏢 Kantoran</a>
                        <a href="{{ route('attendance.history') }}" class="nav-link">📋 Riwayat</a>
                        <a href="{{ route('rapot.user') }}" class="nav-link">📘 Rapot</a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Kanan --}}
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">
                {{ Auth::user()->name }}
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs ml-2">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </span>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center">
                    <span class="mr-2">🚪</span> Logout
                </button>
            </form>
        </div>

    </div>
</nav>

<style>
    .nav-link {
        color: rgb(75 85 99);
        font-weight: 600;
        transition: 0.2s;
    }
    .nav-link:hover {
        color: rgb(37 99 235);
    }
</style>
