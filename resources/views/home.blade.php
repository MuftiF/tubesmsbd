<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PT Sipirok Indah — Solusi Hijau Berkelanjutan</title>
  <meta name="description" content="PT Sipirok Indah: solusi energi terbarukan, konsultasi lingkungan, dan pengelolaan limbah berkelanjutan yang berfokus pada dampak nyata dan keberlanjutan.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root {
      --primary: #2c5e4e;
      --primary-dark: #1f4a3d;
      --primary-light: #eaf4f1;
      --accent: #d4a373;
    }

    html, body {
      height: 100%;
    }

    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: #f9fafb;
    }

    .focus-ring:focus {
      outline: 3px solid rgba(44, 94, 78, 0.2);
      outline-offset: 3px;
    }

    .reveal {
      opacity: 0;
      transform: translateY(12px);
      transition: opacity .5s ease, transform .5s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: none;
    }

    header {
      transition: background-color .25s ease, box-shadow .25s ease, backdrop-filter .25s ease;
    }

    header.compact {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-hover {
      transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease, background-color .22s ease;
    }

    .card-hover:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(44, 94, 78, 0.1);
      border-color: var(--primary-light);
      background-color: #ffffff;
    }

    .btn-primary {
      background: var(--primary);
      color: white;
      transition: all .22s ease;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(44, 94, 78, 0.2);
    }

    .status-badge {
      position: relative;
      overflow: hidden;
    }

    .status-badge::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(44, 94, 78, 0.05) 0%, transparent 100%);
      opacity: 0;
      transition: opacity .3s ease;
    }

    .status-badge:hover::before {
      opacity: 1;
    }
  </style>
</head>
<body class="antialiased text-gray-800">

  <!-- NAVBAR -->
  <header id="site-header" class="fixed top-0 inset-x-0 z-40 bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto flex items-center justify-between px-4 sm:px-6 py-3 md:py-3.5">
      <!-- Logo -->
      <a href="#home" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-[#2c5e4e] transition shadow-sm overflow-hidden">
          <img 
            src="{{ asset('images/Logo 1.jpg') }}" 
            alt="Logo PT Sipirok Indah"
            class="w-full h-full object-contain"
            onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';"
          >
        </div>
        <div class="flex flex-col leading-tight">
          <span class="font-semibold text-base sm:text-lg text-[#2c5e4e] group-hover:text-[#1f4a3d] transition">
            PT Sipirok Indah
          </span>
          <span class="text-[11px] sm:text-xs text-gray-600 tracking-wide">
            Sustainable Plantation & Energy
          </span>
        </div>
      </a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-7 text-sm font-medium" aria-label="Main navigation">
        <a href="#home" class="text-gray-600 hover:text-[#2c5e4e] transition">Beranda</a>
        <a href="#tentang" class="text-gray-600 hover:text-[#2c5e4e] transition">Tentang</a>
        <a href="#aktivitas" class="text-gray-600 hover:text-[#2c5e4e] transition">Aktivitas</a>
        <a href="#layanan" class="text-gray-600 hover:text-[#2c5e4e] transition">Layanan</a>
        <a href="#kontak" class="text-gray-600 hover:text-[#2c5e4e] transition">Kontak</a>
      </nav>

      <!-- CTA + Mobile Toggle -->
      <div class="flex items-center gap-3">
        <a href="#kontak"
           class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold btn-primary shadow-sm">
          Konsultasi Cepat
        </a>
        <button id="nav-toggle"
                class="md:hidden p-2 rounded-md focus-ring text-gray-700"
                aria-expanded="false"
                aria-controls="mobile-menu">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden>
            <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 bg-white">
      <div class="px-4 py-3 flex flex-col gap-1 text-sm">
        <a href="#home" class="px-3 py-2 rounded-md text-gray-800 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]">Beranda</a>
        <a href="#tentang" class="px-3 py-2 rounded-md text-gray-800 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]">Tentang</a>
        <a href="#layanan" class="px-3 py-2 rounded-md text-gray-800 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]">Layanan</a>
        <a href="#aktivitas" class="px-3 py-2 rounded-md text-gray-800 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]">Aktivitas</a>
        <a href="#kontak" class="px-3 py-2 rounded-md text-gray-800 hover:bg-[#eaf4f1] hover:text-[#2c5e4e]">Kontak</a>
      </div>
    </div>
  </header>

  <main class="pt-20 md:pt-24">
    <!-- HERO -->
    <section id="home" class="relative bg-gradient-to-b from-white to-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-[1.3fr,1fr] gap-10 items-center py-12 md:py-16">
          <!-- Text -->
          <div class="reveal">
            <span class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-full bg-[#eaf4f1] text-[#2c5e4e] border border-gray-200 mb-4">
              <span class="w-1.5 h-1.5 rounded-full bg-[#2c5e4e] mr-2"></span>
              Solusi hijau untuk industri sawit & energi
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-[2.6rem] font-bold text-gray-800 leading-tight mb-4">
              Membangun Masa Depan
              <span class="text-[#2c5e4e]">Hijau</span> & Berkelanjutan
            </h1>
            <p class="text-sm sm:text-base text-gray-600 max-w-xl mb-6 leading-relaxed">
              PT Sipirok Indah berfokus pada pengelolaan kebun, energi terbarukan,
              dan pengolahan limbah dengan pendekatan yang realistis, terukur,
              dan berpihak pada keberlanjutan jangka panjang.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
              <a href="#layanan"
                 class="inline-flex items-center justify-center px-6 py-3 rounded-xl btn-primary text-sm font-semibold shadow-md">
                Lihat Layanan Utama
              </a>
              <a href="#tentang"
                 class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-[#2c5e4e] text-sm font-semibold border-2 border-gray-200 hover:bg-gray-50 hover:border-[#eaf4f1] transition-all">
                Tentang Perusahaan
              </a>
            </div>
            <div class="mt-6 flex flex-wrap items-center gap-4 text-xs text-gray-500">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-[#eaf4f1] flex items-center justify-center text-xs text-[#2c5e4e] font-bold">10+</span>
                <span>Proyek keberlanjutan terselesaikan</span>
              </div>
              <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
              <span>Berbasis di Sipirok, melayani skala nasional</span>
            </div>
          </div>

          <!-- Status Card -->
          <div class="reveal">
            <div class="relative group">
              <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6 card-hover status-badge">
                <!-- HEADER -->
                <p class="text-xs font-semibold text-[#2c5e4e] mb-3 tracking-wide uppercase">
                  Ringkasan Aktivitas Lapangan
                </p>
                <div class="flex items-baseline justify-between mb-5">
                  <div>
                    <p class="text-[11px] text-gray-500 mb-1">Status Mingguan</p>
                    <p class="text-2xl font-bold text-gray-800">
                      Stabil
                      <span class="text-sm font-medium text-gray-500 ml-1">operasional normal</span>
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-[11px] text-[#2c5e4e] flex items-center justify-end gap-1 mb-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#2c5e4e] animate-pulse"></span>
                      Tidak ada kendala
                    </p>
                    <p class="text-[11px] text-gray-500">Aktivitas berjalan lancar</p>
                  </div>
                </div>
                <div class="grid grid-cols-3 gap-3 text-[11px]">
                  <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 transition-all group-hover:bg-[#eaf4f1] group-hover:border-transparent">
                    <p class="text-gray-500 mb-1">Monitoring kebun</p>
                    <p class="text-sm font-semibold text-gray-800">Dilaksanakan</p>
                  </div>
                  <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 transition-all group-hover:bg-[#eaf4f1] group-hover:border-transparent">
                    <p class="text-gray-500 mb-1">Kondisi lapangan</p>
                    <p class="text-sm font-semibold text-gray-800">Baik</p>
                  </div>
                  <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 transition-all group-hover:bg-[#eaf4f1] group-hover:border-transparent">
                    <p class="text-gray-500 mb-1">Peralatan & mesin</p>
                    <p class="text-sm font-semibold text-gray-800">Terawat</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-14 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <!-- Heading -->
        <div class="text-center mb-12 reveal">
          <p class="text-xs font-semibold text-[#2c5e4e] tracking-wide uppercase mb-2">Tentang Kami</p>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800">PT Sipirok Indah</h2>
          <p class="text-sm md:text-base text-gray-600 mt-4 max-w-2xl mx-auto leading-relaxed">
            PT Sipirok Indah merupakan perusahaan perkebunan yang berdiri sejak 1991 
            dan memiliki lebih dari 34 tahun pengalaman di industri kelapa sawit. 
            Beroperasi di sektor agribisnis, perusahaan menunjukkan komitmen kuat 
            dalam pengelolaan Sawit Tandan Buah Segar (TBS) secara profesional dan berkelanjutan.
          </p>
        </div>

        <!-- Cards -->
        <div class="grid md:grid-cols-3 gap-6 items-start">
          <!-- Visi -->
          <div class="bg-white border border-gray-200 rounded-2xl p-7 card-hover reveal h-full shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <span class="text-sm font-bold text-[#2c5e4e]">V</span>
              </div>
              <h3 class="font-semibold text-lg text-gray-800">Visi</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              Menjadi korporasi agrobisnis kelapa sawit yang diakui di Indonesia, 
              menguntungkan, serta dikelola dengan terbaik, terintegrasi, dan berkesinambungan.
            </p>
          </div>

          <!-- Misi -->
          <div class="bg-white border border-gray-200 rounded-2xl p-7 card-hover reveal h-full shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <span class="text-sm font-bold text-[#2c5e4e]">M</span>
              </div>
              <h3 class="font-semibold text-lg text-gray-800">Misi</h3>
            </div>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-2 leading-relaxed">
              <li>Mensejahterakan masyarakat sekitar dan karyawan.</li>
              <li>Meningkatkan produksi TBS yang berkualitas dan berkelanjutan.</li>
              <li>Meningkatkan nilai moral dan spiritual karyawan.</li>
              <li>Meningkatkan kompetensi SDM melalui pelatihan berkesinambungan agar profesional dan zero accident.</li>
              <li>Meningkatkan pengelolaan lingkungan, keselamatan, dan kesehatan kerja.</li>
              <li>Menerapkan sistem manajemen terbaik pada seluruh proses perusahaan.</li>
            </ul>
          </div>

          <!-- Nilai -->
          <div class="bg-white border border-gray-200 rounded-2xl p-7 card-hover reveal h-full shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <span class="text-sm font-bold text-[#2c5e4e]">N</span>
              </div>
              <h3 class="font-semibold text-lg text-gray-800">Nilai</h3>
            </div>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-2 leading-relaxed">
              <li>Integritas dalam setiap keputusan.</li>
              <li>Inovasi yang realistis dan terukur.</li>
              <li>Keberlanjutan sebagai prioritas utama.</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- PROGRAM & AKTIVITAS -->
    <section id="aktivitas" class="py-14 md:py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 reveal">
          <p class="text-xs font-semibold text-[#2c5e4e] tracking-wide uppercase mb-2">Program & Aktivitas</p>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
            Komitmen Kami Terhadap Operasional & Masyarakat
          </h2>
          <p class="text-sm md:text-base text-gray-600 mt-4 max-w-2xl mx-auto leading-relaxed">
            Berbagai program rutin dan kegiatan sosial dilakukan sebagai bagian dari tanggung jawab 
            perusahaan dalam menciptakan keberlanjutan, peningkatan kualitas hidup, dan lingkungan kerja yang lebih baik.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Card 1 -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Produk Yang Dihasilkan</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              Perusahaan agribisnis yang berfokus pada pengelolaan perkebunan kelapa sawit berkelanjutan 
              serta menjaga mutu buah kelapa sawit agar sesuai dengan standar industri.
            </p>
          </div>

          <!-- Card 2 -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Tenaga Kerja Kami</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              Tenaga kerja membutuhkan lingkungan yang aman dan kondusif. Kami berupaya menyediakan 
              lingkungan kerja yang setara atau lebih baik dari standar industri.
            </p>
          </div>

          <!-- Card 3 -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Pendidikan</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              Pendidikan berperan penting dalam mengurangi kemiskinan dan meningkatkan kesejahteraan. 
              Kami secara rutin memberikan beasiswa pendidikan untuk anak-anak karyawan yang berprestasi.
            </p>
          </div>

          <!-- Card 4 -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Peduli Kecerdasan Anak</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              PT Sipirok Indah peduli pada perkembangan pendidikan anak-anak masyarakat di sekitar 
              perkebunan, termasuk memberikan dukungan dan bantuan kepada sekolah-sekolah setempat.
            </p>
          </div>

          <!-- Card 5 -->
          <div class="bg-white border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Perbaikan Jalan Umum</h3>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed">
              Sebagai bentuk kontribusi kepada masyarakat, perusahaan turut mendukung perbaikan 
              infrastruktur desa seperti akses jalan umum di Desa Sibargot dan wilayah sekitar kebun.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan" class="py-14 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 reveal">
          <p class="text-xs font-semibold text-[#2c5e4e] tracking-wide uppercase mb-2">Layanan</p>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Solusi yang Kami Tawarkan</h2>
          <p class="text-sm md:text-base text-gray-600 mt-4 max-w-2xl mx-auto leading-relaxed">
            Dirancang untuk kebun, pabrik, dan organisasi yang ingin bergerak menuju operasional yang lebih hijau,
            tanpa kehilangan efisiensi bisnis.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Card 1 -->
          <article class="bg-gray-50 border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" viewBox="0 0 24 24" fill="none">
                  <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Energi Terbarukan</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
              Perencanaan dan instalasi panel surya, sistem mikrohidro, dan integrasi dengan proses produksi
              untuk efisiensi energi.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Studi kelayakan</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Desain sistem</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Pemantauan</span>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="bg-gray-50 border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" viewBox="0 0 24 24" fill="none">
                  <path d="M12 2a10 10 0 100 20 10 10 0 000-20z" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Konsultasi Lingkungan</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
              Audit lingkungan, strategi keberlanjutan, dan pendampingan implementasi kebijakan ESG
              yang relevan dengan operasional sawit.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Audit</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Sertifikasi</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Program edukasi</span>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="bg-gray-50 border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" viewBox="0 0 24 24" fill="none">
                  <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Pengelolaan Limbah</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
              Sistem pengolahan limbah cair & padat, program daur ulang, serta pemanfaatan residu
              menjadi energi atau produk turunan.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Desain IPAL</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Daur ulang</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Pemanfaatan residu</span>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="bg-gray-50 border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" viewBox="0 0 24 24" fill="none">
                  <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Manajemen Kebun</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
              Pendampingan tata kelola kebun yang efisien: dari panen, pencatatan, hingga optimasi
              tenaga kerja dan peralatan.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Perencanaan panen</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Monitoring</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Pelaporan</span>
            </div>
          </article>

          <!-- Card 5 -->
          <article class="bg-gray-50 border border-gray-200 rounded-2xl p-6 card-hover reveal shadow-sm">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#2c5e4e]" viewBox="0 0 24 24" fill="none">
                  <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h3 class="font-semibold text-gray-800 text-base">Pelaporan & Dashboard</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
              Sistem pelaporan digital dan dashboard produksi yang memberikan visibilitas harian
              untuk manajemen dan pemilik.
            </p>
            <div class="flex flex-wrap gap-2">
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Integrasi data</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Analitik</span>
              <span class="text-[11px] px-2.5 py-1 rounded-full bg-white border border-gray-200 text-gray-600">Export laporan</span>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak" class="py-14 md:py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-[1.1fr,1fr] gap-6">
          <!-- Info -->
          <div class="bg-white border border-gray-200 rounded-2xl p-7 shadow-sm reveal">
            <p class="text-xs font-semibold text-[#2c5e4e] uppercase tracking-wide mb-2">Kontak</p>
            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">
              Mulai diskusi dengan tim kami
            </h3>
            <p class="text-sm md:text-base text-gray-600 mb-6 leading-relaxed">
              Sampaikan kebutuhan Anda terkait pengelolaan kebun, energi terbarukan, atau pengolahan limbah.
              Kami akan merespons dengan solusi yang realistis dan terukur.
            </p>

            <div class="space-y-3 text-sm text-gray-700 mb-6">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#eaf4f1] flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-800 mb-1">Alamat</p>
                  <p class="text-gray-600">Jl. DI. Panjaitan No. 180 Kelurahan - Sei Sikambing D, Kecamatan Medan Petisah, Kota Medan, Sumatra Utara</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-800 mb-1">Telepon</p>
                  <p class="text-gray-600">061-4571801</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-800 mb-1">Email</p>
                  <p class="text-gray-600">sipirokindah@gmail.com</p>
                </div>
              </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
              <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2c5e4e] text-xs text-white font-medium hover:bg-[#1f4a3d] transition-all shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
              </a>
              <a href="#" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2c5e4e] text-xs text-white font-medium hover:bg-[#1f4a3d] transition-all shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Instagram
              </a>
            </div>
          </div>

          <!-- Form -->
          <div class="bg-white border border-gray-200 rounded-2xl p-7 shadow-sm reveal">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-2">Kirim Pesan</h3>
            <p class="text-xs text-gray-500 mb-5">
              Form ini bersifat simulasi untuk demo. Integrasi ke backend/email dapat ditambahkan kemudian.
            </p>
            <form id="contact-form" class="space-y-4" novalidate>
              <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block">Nama</label>
                <input name="name" type="text" placeholder="Nama lengkap"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus-ring bg-white hover:border-[#eaf4f1] transition-colors"
                       required>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block">Email</label>
                <input name="email" type="email" placeholder="nama@perusahaan.com"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus-ring bg-white hover:border-[#eaf4f1] transition-colors"
                       required>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-700 mb-2 block">Pesan</label>
                <textarea name="message" rows="4" placeholder="Ceritakan kebutuhan atau pertanyaan Anda..."
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus-ring bg-white hover:border-[#eaf4f1] transition-colors resize-none"
                          required></textarea>
              </div>
              <button type="submit"
                      class="w-full inline-flex items-center justify-center gap-2 btn-primary text-sm font-semibold px-5 py-3 rounded-xl shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Kirim Pesan
              </button>
              <p id="form-msg" class="text-sm text-[#2c5e4e] font-medium hidden mt-3 p-3 bg-[#eaf4f1] rounded-lg border border-gray-200">
                ✓ Pesan terkirim. Terima kasih, tim kami akan segera meninjau.
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-[#1f4a3d] text-white py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
            <span class="text-sm font-bold text-white">SI</span>
          </div>
          <div>
            <p class="font-semibold text-base">PT Sipirok Indah</p>
            <p class="text-xs text-white/70">Sustainable Plantation & Energy</p>
          </div>
        </div>
        <div class="flex gap-3">
          <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
          <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </a>
        </div>
      </div>
      <div class="pt-6 text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>© <span id="year"></span> PT Sipirok Indah. Semua hak dilindungi.</p>
        <p class="text-white/60">
          Dibangun dengan fokus pada keberlanjutan & transparansi data.
        </p>
      </div>
    </div>
  </footer>

  <script>
    (function(){
      const btn = document.getElementById('nav-toggle');
      const menu = document.getElementById('mobile-menu');
      const header = document.getElementById('site-header');

      if(btn && menu){
        btn.addEventListener('click', ()=>{
          const isHidden = menu.classList.toggle('hidden');
          btn.setAttribute('aria-expanded', !isHidden);
        });
      }

      // Smooth scroll dengan offset header
      document.querySelectorAll('a[href^="#"]').forEach(a=>{
        a.addEventListener('click', function(e){
          const hash = this.getAttribute('href');
          const target = document.querySelector(hash);
          if(!target) return;
          e.preventDefault();
          const headerHeight = header.offsetHeight || 64;
          const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 8;
          window.scrollTo({ top, behavior:'smooth' });
          if(!menu.classList.contains('hidden')) menu.classList.add('hidden');
        });
      });

      // Reveal effect
      const reveals = document.querySelectorAll('.reveal');
      const onScroll = ()=>{
        reveals.forEach(r=>{
          const rect = r.getBoundingClientRect();
          if(rect.top < window.innerHeight - 60){
            r.classList.add('visible');
          }
        });
      };
      window.addEventListener('scroll', onScroll, { passive:true });
      window.addEventListener('load', onScroll);

      // Header compact state
      window.addEventListener('scroll', ()=>{
        if(window.scrollY > 80) header.classList.add('compact');
        else header.classList.remove('compact');
      }, { passive:true });

      // Contact form submit
      const form = document.getElementById('contact-form');
      const msg = document.getElementById('form-msg');
      if(form){
        form.addEventListener('submit', function(e){
          e.preventDefault();
          const fd = new FormData(form);
          if(!fd.get('name') || !fd.get('email') || !fd.get('message')){
            alert('Lengkapi semua field terlebih dahulu.');
            return;
          }
          msg.classList.remove('hidden');
          form.reset();
          setTimeout(()=>msg.classList.add('hidden'), 4000);
        });
      }

      // Tahun berjalan
      const yearEl = document.getElementById('year');
      if(yearEl) yearEl.textContent = new Date().getFullYear();
    })();
  </script>
</body>
</html>