<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Sipirok Indah - Solusi Hijau Berkelanjutan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #16a34a;
            --primary-light: #4ade80;
            --primary-dark: #15803d;
            --accent: #f59e0b;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .mobile-menu { max-height: 0; overflow: hidden; transition: max-height 0.5s ease; }
        .mobile-menu.open { max-height: 500px; }
        .hero-bg {
            background: linear-gradient(-45deg, #16a34a, #22c55e, #4ade80, #86efac);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Active nav link styling */
        .nav-link.active {
            color: var(--primary);
            font-weight: bold;
        }
        
        /* Mobile menu button styling */
        #mobile-menu-button {
            display: none;
        }
        
        @media (max-width: 767px) {
            #mobile-menu-button {
                display: block;
            }
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 5s ease-in-out infinite;
        }
        
        /* Card hover effects */
        .service-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .service-card:hover::before {
            left: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        /* Fade in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        
        /* Particle background */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float-particle 15s infinite linear;
        }
        
        @keyframes float-particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Logo styling */
        .logo-placeholder {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="bg-green-50 text-gray-800 font-sans scroll-smooth overflow-x-hidden">

    <!-- Navbar -->
    <header class="bg-white text-green-800 shadow-md fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 py-4">
            <div class="flex items-center space-x-3">
                <!-- Logo Asli -->
                <img src="{{ asset('images/Logo 1.jpg') }}" alt="Logo PT Sipirok Indah" class="w-10 h-10 object-contain rounded-full floating">
                <h1 class="text-xl sm:text-2xl font-bold gradient-text">PT Sipirok Indah</h1>
            </div>

            <nav class="hidden md:flex space-x-6 font-medium">
                <a href="#home" class="nav-link relative hover:text-green-600 transition">Beranda</a>
                <a href="#tentang" class="nav-link relative hover:text-green-600 transition">Tentang</a>
                <a href="#layanan" class="nav-link relative hover:text-green-600 transition">Layanan</a>
                <a href="#grafik" class="nav-link relative hover:text-green-600 transition">Grafik</a>
                <a href="#kontak" class="nav-link relative hover:text-green-600 transition">Kontak</a>
            </nav>

            <!-- Hamburger -->
            <button id="mobile-menu-button" class="text-green-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="mobile-menu md:hidden bg-white">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#home" class="block py-2 px-4 text-green-800 hover:bg-green-50 rounded-md transition flex items-center">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
                <a href="#tentang" class="block py-2 px-4 text-green-800 hover:bg-green-50 rounded-md transition flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Tentang
                </a>
                <a href="#layanan" class="block py-2 px-4 text-green-800 hover:bg-green-50 rounded-md transition flex items-center">
                    <i class="fas fa-cogs mr-2"></i> Layanan
                </a>
                <a href="#grafik" class="block py-2 px-4 text-green-800 hover:bg-green-50 rounded-md transition flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i> Grafik
                </a>
                <a href="#kontak" class="block py-2 px-4 text-green-800 hover:bg-green-50 rounded-md transition flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Kontak
                </a>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section id="home" class="flex flex-col items-center justify-center text-center hero-bg text-white min-h-screen px-6 pt-20 relative overflow-hidden">
        <div class="particles" id="particles"></div>
        <div class="relative z-10 max-w-4xl">
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mb-6 leading-tight fade-in">Membangun Masa Depan yang <span class="text-yellow-300">Hijau</span> dan Berkelanjutan</h2>
            <p class="text-lg sm:text-xl md:text-2xl max-w-3xl mb-10 fade-in">
                Kami berkomitmen untuk menciptakan solusi ramah lingkungan dan mendukung pembangunan berkelanjutan di Indonesia.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in">
                <a href="#tentang" class="bg-white text-green-700 px-8 py-4 rounded-full font-semibold hover:bg-green-100 transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                    <i class="fas fa-leaf mr-2"></i> Pelajari Lebih Lanjut
                </a>
                <a href="#kontak" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-green-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                    <i class="fas fa-handshake mr-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#tentang" class="text-white text-2xl">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h3 class="text-4xl font-bold text-green-700 mb-4">Tentang Kami</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    PT Sipirok Indah adalah perusahaan yang berdedikasi dalam pengembangan energi terbarukan, pengelolaan sumber daya alam, 
                    dan inovasi ramah lingkungan.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="fade-in">
                    <div class="bg-gradient-to-br from-green-100 to-yellow-50 p-8 rounded-2xl shadow-lg">
                        <h4 class="text-2xl font-bold text-green-800 mb-4">Visi & Misi</h4>
                        <p class="text-gray-700 mb-6">
                            Kami percaya kemajuan ekonomi dapat berjalan selaras dengan pelestarian alam. Visi kami adalah menciptakan Indonesia yang hijau dan berkelanjutan untuk generasi mendatang.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Mengembangkan solusi energi terbarukan yang terjangkau</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Mendorong praktik bisnis yang ramah lingkungan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>Meningkatkan kesadaran masyarakat tentang keberlanjutan</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="bg-green-700 text-white p-8 rounded-2xl shadow-lg relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-green-800 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-green-800 rounded-full opacity-20"></div>
                        <div class="relative z-10">
                            <h4 class="text-2xl font-bold mb-4">Nilai Perusahaan</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold">Integritas</h5>
                                        <p class="text-green-100 text-sm">Menjunjung tinggi kejujuran dan transparansi</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold">Inovasi</h5>
                                        <p class="text-green-100 text-sm">Terus mengembangkan solusi berkelanjutan</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold">Keberlanjutan</h5>
                                        <p class="text-green-100 text-sm">Berorientasi pada masa depan yang lebih baik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section id="layanan" class="py-20 bg-gradient-to-b from-white to-green-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h3 class="text-4xl font-bold text-green-700 mb-4">Layanan Kami</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Kami menyediakan berbagai solusi hijau untuk membantu bisnis dan komunitas menerapkan praktik berkelanjutan.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="service-card bg-white p-8 rounded-2xl shadow-lg border border-green-100 fade-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-700 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-solar-panel"></i>
                    </div>
                    <h4 class="text-xl font-bold text-center text-green-800 mb-4">Energi Terbarukan</h4>
                    <p class="text-gray-700 text-center mb-6">Menghadirkan solusi energi bersih dari sumber daya matahari, angin, dan air untuk kebutuhan rumah tangga dan industri.</p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Pemasangan panel surya</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Sistem energi mikrohidro</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Konsultasi efisiensi energi</span>
                        </li>
                    </ul>
                </div>
                
                <div class="service-card bg-white p-8 rounded-2xl shadow-lg border border-green-100 fade-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-700 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4 class="text-xl font-bold text-center text-green-800 mb-4">Konsultasi Lingkungan</h4>
                    <p class="text-gray-700 text-center mb-6">Membantu perusahaan menerapkan praktik bisnis ramah lingkungan dan memenuhi regulasi keberlanjutan.</p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Audit lingkungan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Strategi keberlanjutan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Pelatihan SDM</span>
                        </li>
                    </ul>
                </div>
                
                <div class="service-card bg-white p-8 rounded-2xl shadow-lg border border-green-100 fade-in">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-700 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h4 class="text-xl font-bold text-center text-green-800 mb-4">Pengelolaan Limbah</h4>
                    <p class="text-gray-700 text-center mb-6">Solusi efektif dan aman untuk pengolahan limbah industri dengan pendekatan ekonomi sirkular.</p>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Pengolahan limbah B3</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Daur ulang terpadu</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Sistem pengomposan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Grafik -->
    <section id="grafik" class="py-20 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h3 class="text-4xl font-bold text-green-700 mb-4">Grafik Pengumpulan Sawit per Hari</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Pantau perkembangan produksi sawit harian kami yang dikelola dengan prinsip berkelanjutan.
                </p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 fade-in">
                <canvas id="grafikSawit" class="mx-auto w-full h-[400px]"></canvas>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500 fade-in">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800">Produktivitas Tinggi</h4>
                            <p class="text-sm text-gray-600">Rata-rata 160 ton/hari</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500 fade-in">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 mr-4">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800">Ramah Lingkungan</h4>
                            <p class="text-sm text-gray-600">Praktik berkelanjutan</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500 fade-in">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800">Kemitraan Petani</h4>
                            <p class="text-sm text-gray-600">200+ petani lokal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-20 bg-gradient-to-br from-green-700 to-green-900 text-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h3 class="text-4xl font-bold mb-4">Hubungi Kami</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-green-400 to-yellow-400 mx-auto mb-6"></div>
                <p class="text-green-100 max-w-2xl mx-auto">
                    Ingin tahu lebih banyak tentang layanan kami? Kami siap membantu Anda mewujudkan bisnis yang lebih hijau dan berkelanjutan.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="fade-in">
                    <div class="bg-green-800 bg-opacity-50 p-8 rounded-2xl shadow-lg">
                        <h4 class="text-2xl font-bold mb-6">Informasi Kontak</h4>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center text-white mr-4">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold">Alamat</h5>
                                    <p class="text-green-100">Jl. Hijau Lestari No. 123, Sipirok, Sumatera Utara</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center text-white mr-4">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold">Telepon</h5>
                                    <p class="text-green-100">+62 812 3456 7890</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center text-white mr-4">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold">Email</h5>
                                    <p class="text-green-100">info@ptsipirokindah.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center text-white mr-4">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h5 class="font-bold">Jam Operasional</h5>
                                    <p class="text-green-100">Senin - Jumat: 08.00 - 17.00 WIB</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h5 class="font-bold mb-4">Ikuti Kami</h5>
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="fade-in">
                    <div class="bg-white text-gray-800 p-8 rounded-2xl shadow-lg">
                        <h4 class="text-2xl font-bold text-green-800 mb-6">Kirim Pesan</h4>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-2" for="name">Nama Lengkap</label>
                                <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Masukkan nama Anda">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2" for="email">Email</label>
                                <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Masukkan email Anda">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2" for="subject">Subjek</label>
                                <input type="text" id="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Subjek pesan">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2" for="message">Pesan</label>
                                <textarea id="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Tulis pesan Anda di sini"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-900 text-white py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="fade-in">
                    <div class="flex items-center space-x-3 mb-4">
                        <!-- Logo Asli di Footer -->
                        <img src="{{ asset('images/Logo 1.jpg') }}" alt="Logo PT Sipirok Indah" class="w-10 h-10 object-contain rounded-full">
                        <h3 class="text-xl font-bold">PT Sipirok Indah</h3>
                    </div>
                    <p class="text-green-200 text-sm">
                        Perusahaan yang berdedikasi untuk menciptakan solusi ramah lingkungan dan mendukung pembangunan berkelanjutan di Indonesia.
                    </p>
                </div>
                
                <div class="fade-in">
                    <h4 class="text-lg font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-green-200 hover:text-white transition">Beranda</a></li>
                        <li><a href="#tentang" class="text-green-200 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#layanan" class="text-green-200 hover:text-white transition">Layanan</a></li>
                        <li><a href="#grafik" class="text-green-200 hover:text-white transition">Grafik</a></li>
                        <li><a href="#kontak" class="text-green-200 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="fade-in">
                    <h4 class="text-lg font-bold mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-green-200 hover:text-white transition">Energi Terbarukan</a></li>
                        <li><a href="#" class="text-green-200 hover:text-white transition">Konsultasi Lingkungan</a></li>
                        <li><a href="#" class="text-green-200 hover:text-white transition">Pengelolaan Limbah</a></li>
                        <li><a href="#" class="text-green-200 hover:text-white transition">Audit Keberlanjutan</a></li>
                    </ul>
                </div>
                
                <div class="fade-in">
                    <h4 class="text-lg font-bold mb-4">Berlangganan</h4>
                    <p class="text-green-200 text-sm mb-4">Dapatkan informasi terbaru tentang keberlanjutan dan layanan kami.</p>
                    <div class="flex">
                        <input type="email" placeholder="Email Anda" class="px-4 py-2 w-full rounded-l-lg focus:outline-none text-gray-800">
                        <button class="bg-green-600 hover:bg-green-500 px-4 rounded-r-lg transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-green-800 mt-8 pt-8 text-center text-green-300 text-sm">
                <p>© <span id="current-year"></span> PT Sipirok Indah. Semua hak dilindungi undang-undang.</p>
            </div>
        </div>
    </footer>

    <script>
        // === Mobile Menu ===
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
            });
            
            // Close mobile menu when clicking on a link
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('open');
                });
            });
        }

        // === Smooth Scroll ===
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Calculate the position to scroll to
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight;
                    
                    // Smooth scroll to the target
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // === Fade In Animation on Scroll ===
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInOnScroll = () => {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('visible');
                }
            });
        };
        
        window.addEventListener('scroll', fadeInOnScroll);
        // Initial check on page load
        fadeInOnScroll();

        // === Grafik Sawit ===
        const ctx = document.getElementById('grafikSawit');
        if (ctx) {
            const chartCtx = ctx.getContext('2d');
            const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(34,197,94,0.8)');
            gradient.addColorStop(1, 'rgba(187,247,208,0.8)');

            new Chart(chartCtx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Jumlah Sawit (ton)',
                        data: [120, 150, 180, 140, 200, 170, 160],
                        backgroundColor: gradient,
                        borderColor: '#16a34a',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#166534', font: { weight: '600' } },
                            title: { display: true, text: 'Ton', color: '#14532D', font: { size: 14, weight: 'bold' } }
                        },
                        x: {
                            ticks: { color: '#166534', font: { weight: '600' } }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#166534', font: { weight: 'bold' } } },
                        tooltip: {
                            backgroundColor: '#16a34a',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            cornerRadius: 8,
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // === Update current year in footer ===
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // === Particle Background ===
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size
                const size = Math.random() * 20 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                
                // Random animation duration
                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // Random delay
                const delay = Math.random() * 5;
                particle.style.animationDelay = `${delay}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
        
        createParticles();

        // === Navbar scroll effect ===
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('shadow-lg');
                header.classList.add('py-3');
                header.classList.remove('py-4');
            } else {
                header.classList.remove('shadow-lg');
                header.classList.remove('py-3');
                header.classList.add('py-4');
            }
        });
    </script>

</body>
</html>