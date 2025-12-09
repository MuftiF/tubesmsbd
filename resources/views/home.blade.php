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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root{
      --green-primary:#059669;
      --green-dark:#065f46;
      --green-light:#d1fae5;
      --brand-shadow:0 18px 40px rgba(5, 150, 105, 0.15);
    }

    html,body{
      height:100%;
    }

    body{
      font-family:'Plus Jakarta Sans',system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
      background: radial-gradient(circle at top left,#f0fdf4 0,#f9fafb 52%,#ecfdf5 100%);
    }

    .focus-ring:focus{
      outline:3px solid rgba(5, 150, 105, 0.2);
      outline-offset:3px;
    }

    .reveal{
      opacity:0;
      transform:translateY(12px);
      transition:opacity .5s ease,transform .5s ease;
    }
    .reveal.visible{
      opacity:1;
      transform:none;
    }

    header{
      transition:background-color .25s ease,box-shadow .25s ease,backdrop-filter .25s ease;
    }
    header.compact{
      background-color:rgba(255,255,255,0.92);
      backdrop-filter:blur(10px);
      box-shadow:0 12px 30px rgba(15,23,42,0.08);
    }

    .chart-h{
      height:320px;
    }

    /* Card hover lembut */
    .card-soft{
      transition:transform .22s ease,box-shadow .22s ease,background-color .22s ease;
    }
    .card-soft:hover{
      transform:translateY(-4px);
      box-shadow:var(--brand-shadow);
      background-color:#f9fafb;
    }
    
    /* Gradient hijau yang lebih harmonis */
    .green-gradient {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }
    
    /* Background pattern hijau yang sangat subtle */
    .green-pattern {
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23059669' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
  </style>
</head>
<body class="antialiased text-gray-800 green-pattern">

  <!-- NAVBAR -->
  <header id="site-header" class="fixed top-0 inset-x-0 z-40 border-b border-emerald-100 bg-white/90 backdrop-blur">
    <div class="max-w-6xl mx-auto flex items-center justify-between px-4 sm:px-6 py-3 md:py-3.5">

        <!-- Logo -->
        <a href="#home" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center ring-2 ring-emerald-200 group-hover:ring-emerald-500 transition shadow-sm overflow-hidden">
                <img 
                    src="{{ asset('images/Logo 1.jpg') }}" 
                    alt="Logo PT Sipirok Indah"
                    class="w-full h-full object-contain"
                    onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';"
                />
      <span class="text-emerald-700 font-bold text-lg hidden">SI</span>
    </div>
    <div class="flex flex-col leading-tight">
      <span class="font-semibold text-base sm:text-lg text-emerald-800 group-hover:text-emerald-900 transition">
        PT Sipirok Indah
      </span>
      <span class="text-[11px] sm:text-xs text-emerald-600 tracking-wide">
        Sustainable Plantation & Energy
      </span>
    </div>
  </a>
      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-7 text-sm font-medium" aria-label="Main navigation">
        <a href="#home" class="text-gray-600 hover:text-emerald-700 transition">Beranda</a>
        <a href="#tentang" class="text-gray-600 hover:text-emerald-700 transition">Tentang</a>
        <a href="#aktivitas" class="text-gray-600 hover:text-emerald-700 transition">Aktivitas</a>
        <a href="#layanan" class="text-gray-600 hover:text-emerald-700 transition">Layanan</a>
        <a href="#kontak" class="text-gray-600 hover:text-emerald-700 transition">Kontak</a>
      </nav>

      <!-- CTA + Mobile Toggle -->
      <div class="flex items-center gap-3">
        <a href="#kontak"
           class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-semibold green-gradient text-white shadow-sm hover:shadow-md transition-all">
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
    <div id="mobile-menu" class="md:hidden hidden border-t border-emerald-100 bg-white/95 backdrop-blur">
      <div class="px-4 py-3 flex flex-col gap-1 text-sm">
        <a href="#home" class="px-3 py-2 rounded-md text-gray-800 hover:bg-emerald-50 hover:text-emerald-700">Beranda</a>
        <a href="#tentang" class="px-3 py-2 rounded-md text-gray-800 hover:bg-emerald-50 hover:text-emerald-700">Tentang</a>
        <a href="#layanan" class="px-3 py-2 rounded-md text-gray-800 hover:bg-emerald-50 hover:text-emerald-700">Layanan</a>
        <a href="#aktivitas" class="px-3 py-2 rounded-md text-gray-800 hover:bg-emerald-50 hover:text-emerald-700">Aktivitas</a>
        <a href="#kontak" class="px-3 py-2 rounded-md text-gray-800 hover:bg-emerald-50 hover:text-emerald-700">Kontak</a>
      </div>
    </div>
  </header>

  <main class="pt-20 md:pt-24">

    <!-- HERO -->
    <section id="home" class="relative">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-[1.3fr,1fr] gap-10 items-center py-12 md:py-16">
          <!-- Text -->
          <div class="reveal">
            <p class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 mb-3">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
              Solusi hijau untuk industri sawit & energi
            </p>
            <h1 class="text-3xl sm:text-4xl md:text-[2.6rem] font-extrabold text-slate-900 leading-tight mb-3">
              Membangun Masa Depan
              <span class="text-emerald-600">Hijau</span> & Berkelanjutan
            </h1>
            <p class="text-sm sm:text-base text-slate-600 max-w-xl mb-6">
              PT Sipirok Indah berfokus pada pengelolaan kebun, energi terbarukan,
              dan pengolahan limbah dengan pendekatan yang realistis, terukur,
              dan berpihak pada keberlanjutan jangka panjang.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
              <a href="#layanan"
                 class="inline-flex items-center justify-center px-5 py-3 rounded-full green-gradient text-white text-sm font-semibold shadow-[var(--brand-shadow)] hover:shadow-lg transition-all">
                Lihat Layanan Utama
              </a>
              <a href="#tentang"
                 class="inline-flex items-center justify-center px-5 py-3 rounded-full bg-white text-emerald-700 text-sm font-semibold border border-emerald-200 hover:bg-emerald-50 transition">
                Tentang Perusahaan
              </a>
            </div>
            <div class="mt-6 flex flex-wrap items-center gap-4 text-xs text-slate-500">
              <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-[11px] text-emerald-700 font-semibold">10+</span>
                <span>Proyek keberlanjutan terselesaikan</span>
              </div>
              <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-slate-300"></span>
              <span>Berbasis di Sipirok, melayani skala nasional</span>
            </div>
          </div>

          <!-- Illustration / Stats -->
          <div class="reveal">
            <div class="relative group">
              <div class="rounded-2xl bg-white shadow-[var(--brand-shadow)] p-5 border border-emerald-100 cursor-pointer">
                <p class="text-xs font-semibold text-emerald-700 mb-2">Ringkasan Produksi Mingguan</p>
                <div class="flex items-baseline justify-between mb-4">
                  <div>
                    <p class="text-[11px] text-slate-500">Total panen minggu ini</p>
                    <p class="text-2xl font-extrabold text-slate-900">1.120
                      <span class="text-base font-semibold text-slate-500"> ton</span>
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-[11px] text-emerald-600 flex items-center justify-end gap-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                      +8.4% dari minggu lalu
                    </p>
                    <p class="text-[11px] text-slate-500">Stabil & bertumbuh</p>
                  </div>
                </div>

                <div class="grid grid-cols-3 gap-3 text-[11px]">
                  <div class="p-3 rounded-xl bg-emerald-50">
                    <p class="text-slate-500 mb-1">Kebun aktif</p>
                    <p class="text-base font-semibold text-slate-900">12 lokasi</p>
                  </div>
                  <div class="p-3 rounded-xl bg-emerald-50">
                    <p class="text-slate-500 mb-1">Tingkat pemanfaatan</p>
                    <p class="text-base font-semibold text-slate-900">87%</p>
                  </div>
                  <div class="p-3 rounded-xl bg-emerald-50">
                    <p class="text-slate-500 mb-1">Emisi ditekan</p>
                    <p class="text-base font-semibold text-slate-900">−21%</p>
                  </div>
                </div>
              </div>

              <!-- Tooltip Fixed -->
              <div class="absolute right-0 bottom-0 translate-x-6 translate-y-6 w-40 rounded-2xl 
                          bg-slate-900 text-slate-100 p-4 shadow-2xl opacity-0 scale-95
                          transition-all duration-300 ease-out pointer-events-none
                          group-hover:opacity-100 group-hover:scale-100 hidden sm:block">
                <p class="text-[11px] text-emerald-300 mb-1">Status hari ini</p>
                <p class="text-sm font-semibold mb-2">Operasional Normal</p>
                <p class="text-[11px] text-slate-300">
                  Cuaca mendukung, aktivitas panen & pengolahan berjalan sesuai jadwal.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-14 md:py-20">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <!-- Heading -->
        <div class="text-center mb-10 reveal">
          <p class="text-xs font-semibold text-emerald-600 tracking-wide uppercase">Tentang Kami</p>
          <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-1">PT Sipirok Indah</h2>
          <p class="text-sm md:text-base text-slate-600 mt-3 max-w-2xl mx-auto leading-relaxed">
            PT Sipirok Indah merupakan perusahaan perkebunan yang berdiri sejak 1991 
            dan memiliki lebih dari 34 tahun pengalaman di industri kelapa sawit. 
            Beroperasi di sektor agribisnis, perusahaan menunjukkan komitmen kuat 
            dalam pengelolaan Sawit Tandan Buah Segar (TBS) secara profesional dan berkelanjutan.
          </p>
        </div>

        <!-- Cards -->
        <div class="grid md:grid-cols-3 gap-7 items-start">

          <!-- Visi -->
          <div class="bg-white border border-emerald-100 rounded-2xl p-7 card-soft reveal h-full">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-[13px] text-emerald-700">V</span>
              Visi
            </h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Menjadi korporasi agrobisnis kelapa sawit yang diakui di Indonesia, 
              menguntungkan, serta dikelola dengan terbaik, terintegrasi, dan berkesinambungan.
            </p>
          </div>

          <!-- Misi -->
          <div class="bg-white border border-emerald-100 rounded-2xl p-7 card-soft reveal h-full">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-[13px] text-emerald-700">M</span>
              Misi
            </h3>
            <ul class="list-disc pl-5 text-sm text-slate-600 space-y-2 leading-relaxed">
              <li>Mensejahterakan masyarakat sekitar dan karyawan.</li>
              <li>Meningkatkan produksi TBS yang berkualitas dan berkelanjutan.</li>
              <li>Meningkatkan nilai moral dan spiritual karyawan.</li>
              <li>Meningkatkan kompetensi SDM melalui pelatihan berkesinambungan agar profesional dan zero accident.</li>
              <li>Meningkatkan pengelolaan lingkungan, keselamatan, dan kesehatan kerja.</li>
              <li>Menerapkan sistem manajemen terbaik pada seluruh proses perusahaan.</li>
            </ul>
          </div>

          <!-- Nilai -->
          <div class="bg-white border border-emerald-100 rounded-2xl p-7 card-soft reveal h-full">
            <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-[13px] text-emerald-700">N</span>
              Nilai
            </h3>
            <ul class="list-disc pl-5 text-sm text-slate-600 space-y-2 leading-relaxed">
              <li>Integritas dalam setiap keputusan.</li>
              <li>Inovasi yang realistis dan terukur.</li>
              <li>Keberlanjutan sebagai prioritas utama.</li>
            </ul>
          </div>

        </div>
      </div>
    </section>

    <!-- PROGRAM & AKTIVITAS -->
    <section id="aktivitas" class="py-14 md:py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12 reveal">
          <p class="text-xs font-semibold text-emerald-600 tracking-wide uppercase">Program & Aktivitas</p>
          <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1">
            Komitmen Kami Terhadap Operasional & Masyarakat
          </h2>
          <p class="text-sm md:text-base text-slate-600 mt-3 max-w-2xl mx-auto leading-relaxed">
            Berbagai program rutin dan kegiatan sosial dilakukan sebagai bagian dari tanggung jawab 
            perusahaan dalam menciptakan keberlanjutan, peningkatan kualitas hidup, dan lingkungan kerja yang lebih baik.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">

          <!-- Produk Yang Dihasilkan -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 card-soft reveal">
            <h3 class="font-semibold text-slate-900 text-lg mb-2">Produk Yang Dihasilkan</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Perusahaan agribisnis yang berfokus pada pengelolaan perkebunan kelapa sawit berkelanjutan 
              serta menjaga mutu buah kelapa sawit agar sesuai dengan standar industri.
            </p>
          </div>

          <!-- Tenaga Kerja Kami -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 card-soft reveal">
            <h3 class="font-semibold text-slate-900 text-lg mb-2">Tenaga Kerja Kami</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Tenaga kerja membutuhkan lingkungan yang aman dan kondusif. Kami berupaya menyediakan 
              lingkungan kerja yang setara atau lebih baik dari standar industri.
            </p>
          </div>

          <!-- Pendidikan -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 card-soft reveal">
            <h3 class="font-semibold text-slate-900 text-lg mb-2">Pendidikan</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Pendidikan berperan penting dalam mengurangi kemiskinan dan meningkatkan kesejahteraan. 
              Kami secara rutin memberikan beasiswa pendidikan untuk anak-anak karyawan yang berprestasi.
            </p>
          </div>

          <!-- Peduli Kecerdasan Anak-Anak -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 card-soft reveal">
            <h3 class="font-semibold text-slate-900 text-lg mb-2">Peduli Terhadap Kecerdasan Anak-anak</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              PT Sipirok Indah peduli pada perkembangan pendidikan anak-anak masyarakat di sekitar 
              perkebunan, termasuk memberikan dukungan dan bantuan kepada sekolah-sekolah setempat.
            </p>
          </div>

          <!-- Perbaikan Jalan Umum -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 card-soft reveal">
            <h3 class="font-semibold text-slate-900 text-lg mb-2">Perbaikan Jalan Umum</h3>
            <p class="text-sm text-slate-600 leading-relaxed">
              Sebagai bentuk kontribusi kepada masyarakat, perusahaan turut mendukung perbaikan 
              infrastruktur desa seperti akses jalan umum di Desa Sibargot dan wilayah sekitar kebun.
            </p>
          </div>

        </div>
      </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan" class="py-12 md:py-16 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8 reveal">
          <p class="text-xs font-semibold text-emerald-600 tracking-wide uppercase">Layanan</p>
          <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1">Solusi yang Kami Tawarkan</h2>
          <p class="text-sm md:text-base text-slate-600 mt-2 max-w-2xl mx-auto">
            Dirancang untuk kebun, pabrik, dan organisasi yang ingin bergerak menuju operasional yang lebih hijau,
            tanpa kehilangan efisiensi bisnis.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
          <!-- Card 1 -->
          <article class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 card-soft reveal">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <path d="M4 12h16M4 7h16M4 17h10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-slate-900 text-sm">Energi Terbarukan</h3>
            </div>
            <p class="text-sm text-slate-600 mb-3">
              Perencanaan dan instalasi panel surya, sistem mikrohidro, dan integrasi dengan proses produksi
              untuk efisiensi energi.
            </p>
            <p class="text-[11px] text-emerald-700 font-medium">
              • Studi kelayakan • Desain sistem • Pemantauan performa
            </p>
          </article>

          <!-- Card 2 -->
          <article class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 card-soft reveal">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6"></circle>
                  <path d="M12 8v4l2.5 2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-slate-900 text-sm">Konsultasi Lingkungan</h3>
            </div>
            <p class="text-sm text-slate-600 mb-3">
              Audit lingkungan, strategi keberlanjutan, dan pendampingan implementasi kebijakan ESG
              yang relevan dengan operasional sawit.
            </p>
            <p class="text-[11px] text-emerald-700 font-medium">
              • Audit • Sertifikasi • Program edukasi internal
            </p>
          </article>

          <!-- Card 3 -->
          <article class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 card-soft reveal">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.6"></rect>
                  <path d="M9 9h6v6H9z" stroke="currentColor" stroke-width="1.4"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-slate-900 text-sm">Pengelolaan Limbah</h3>
            </div>
            <p class="text-sm text-slate-600 mb-3">
              Sistem pengolahan limbah cair & padat, program daur ulang, serta pemanfaatan residu
              menjadi energi atau produk turunan.
            </p>
            <p class="text-[11px] text-emerald-700 font-medium">
              • Desain IPAL • Daur ulang • Pemanfaatan residu
            </p>
          </article>

          <!-- Card 4 -->
          <article class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 card-soft reveal">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <path d="M5 20v-8l7-8 7 8v8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M9 20v-5h6v5" stroke="currentColor" stroke-width="1.6"></path>
                </svg>
              </div>
              <h3 class="font-semibold text-slate-900 text-sm">Manajemen Kebun</h3>
            </div>
            <p class="text-sm text-slate-600 mb-3">
              Pendampingan tata kelola kebun yang efisien: dari panen, pencatatan, hingga optimasi
              tenaga kerja dan peralatan.
            </p>
            <p class="text-[11px] text-emerald-700 font-medium">
              • Perencanaan panen • Monitoring • Pelaporan
            </p>
          </article>

          <!-- Card 5 -->
          <article class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 card-soft reveal">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <path d="M6 8h12M6 12h8M6 16h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path>
                  <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.4"></rect>
                </svg>
              </div>
              <h3 class="font-semibold text-slate-900 text-sm">Pelaporan & Dashboard</h3>
            </div>
            <p class="text-sm text-slate-600 mb-3">
              Sistem pelaporan digital dan dashboard produksi yang memberikan visibilitas harian
              untuk manajemen dan pemilik.
            </p>
            <p class="text-[11px] text-emerald-700 font-medium">
              • Integrasi data • Analitik • Export laporan
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak" class="py-12 md:py-16 bg-gradient-to-b from-emerald-50/70 to-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-[1.1fr,1fr] gap-6 md:gap-8">
          <!-- Info -->
          <div class="bg-white/95 border border-emerald-100 rounded-2xl p-6 md:p-7 shadow-sm reveal">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Kontak</p>
            <h3 class="text-xl md:text-2xl font-bold text-slate-900 mt-1 mb-3">
              Mulai diskusi dengan tim kami
            </h3>
            <p class="text-sm md:text-base text-slate-600 mb-5">
              Sampaikan kebutuhan Anda terkait pengelolaan kebun, energi terbarukan, atau pengolahan limbah.
              Kami akan merespons dengan solusi yang realistis dan terukur.
            </p>

            <div class="space-y-3 text-sm text-slate-700 mb-5">
              <p><span class="font-semibold text-slate-900">Alamat:</span>Jl. DI. Panjaitan No. 180 Kelurahan - Sei Sikambing D,Kecamatan Medan Petisah
                                                                          Kota - Medan, Sumatra utara</p>
              <p><span class="font-semibold text-slate-900">Telepon:</span> 061-4571801</p>
              <p><span class="font-semibold text-slate-900">Email:</span> sipirokindah@gmail.com</p>
            </div>

            <div class="flex gap-2 mt-4">
              <a aria-label="facebook" href="#" class="px-3 py-2 rounded-full bg-emerald-600 text-xs text-white hover:bg-emerald-700 transition">Facebook</a>
              <a aria-label="instagram" href="#" class="px-3 py-2 rounded-full bg-emerald-600 text-xs text-white hover:bg-emerald-700 transition">Instagram</a>
            </div>
          </div>

          <!-- Form -->
          <div class="bg-white/95 border border-emerald-100 rounded-2xl p-6 md:p-7 shadow-sm reveal">
            <h3 class="text-lg md:text-xl font-semibold text-slate-900 mb-2">Kirim Pesan</h3>
            <p class="text-xs text-slate-500 mb-4">
              Form ini bersifat simulasi untuk demo. Integrasi ke backend/email dapat ditambahkan kemudian.
            </p>
            <form id="contact-form" class="space-y-3" novalidate>
              <div>
                <label class="text-xs font-medium text-slate-600 mb-1 block">Nama</label>
                <input name="name" type="text" placeholder="Nama lengkap"
                       class="w-full px-3 py-2 border border-emerald-200 rounded-lg text-sm focus-ring bg-emerald-50/60"
                       required>
              </div>
              <div>
                <label class="text-xs font-medium text-slate-600 mb-1 block">Email</label>
                <input name="email" type="email" placeholder="nama@perusahaan.com"
                       class="w-full px-3 py-2 border border-emerald-200 rounded-lg text-sm focus-ring bg-emerald-50/60"
                       required>
              </div>
              <div>
                <label class="text-xs font-medium text-slate-600 mb-1 block">Pesan</label>
                <textarea name="message" rows="4" placeholder="Ceritakan kebutuhan atau pertanyaan Anda..."
                          class="w-full px-3 py-2 border border-emerald-200 rounded-lg text-sm focus-ring bg-emerald-50/60"
                          required></textarea>
              </div>
              <button type="submit"
                      class="w-full inline-flex items-center justify-center gap-2 green-gradient hover:shadow-md text-white text-sm font-semibold px-4 py-2.5 rounded-full shadow-sm transition-all">
                Kirim Pesan
              </button>
              <p id="form-msg" class="text-xs text-emerald-700 hidden mt-2">
                Pesan terkirim. Terima kasih, tim kami akan segera meninjau.
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-200 py-6 mt-4">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-xs sm:text-sm flex flex-col sm:flex-row items-center justify-between gap-2">
      <p>© <span id="year"></span> PT Sipirok Indah. Semua hak dilindungi.</p>
      <p class="text-slate-400">
        Dibangun dengan fokus pada keberlanjutan & transparansi data.
      </p>
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

      // Fake contact form submit
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
          setTimeout(()=>msg.classList.add('hidden'), 3500);
        });
      }

      // Tahun berjalan
      const yearEl = document.getElementById('year');
      if(yearEl) yearEl.textContent = new Date().getFullYear();
    })();

  </script>
</body>
</html>