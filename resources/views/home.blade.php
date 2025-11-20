<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PT Sipirok Indah — Solusi Hijau</title>
  <meta name="description" content="PT Sipirok Indah: solusi energi terbarukan, konsultasi lingkungan, dan pengelolaan limbah berkelanjutan.">

  <!-- Tailwind CDN (small, production-friendly) -->
  <script src="https://cdn.tailwindcss.com" defer></script>
  <!-- Chart.js (defer) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

  <style>
    /* Minimal custom styles to keep small and mobile-first */
    :root{--green:#16a34a;--green-dark:#14532d}
    html,body{height:100%}
    .focus-ring:focus{outline:3px solid rgba(22,163,74,0.15);outline-offset:3px}
    /* simple visible fade for progressive reveal */
    .reveal{opacity:0;transform:translateY(10px);transition:opacity .5s ease,transform .5s ease}
    .reveal.visible{opacity:1;transform:none}
    /* make header compact on scroll */
    header.compact{backdrop-filter: blur(6px);background-color:rgba(255,255,255,0.8)}
    /* keep chart responsive height */
    .chart-h{height:320px}
  </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50">

  <!-- Navbar -->
  <header id="site-header" class="fixed w-full z-40 top-0">
    <div class="max-w-5xl mx-auto flex items-center justify-between px-4 py-3 md:py-4">
      <a href="#home" class="flex items-center gap-3">
        <img loading="lazy" src="/images/logo-small.png" alt="Logo PT Sipirok Indah" class="w-10 h-10 rounded-full object-cover" onerror="this.style.display='none'">
        <span class="font-semibold text-lg text-[var(--green)]">PT Sipirok Indah</span>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm font-medium" aria-label="Main navigation">
        <a href="#home" class="nav-link hover:text-[var(--green)]">Beranda</a>
        <a href="#tentang" class="nav-link hover:text-[var(--green)]">Tentang</a>
        <a href="#layanan" class="nav-link hover:text-[var(--green)]">Layanan</a>
        <a href="#grafik" class="nav-link hover:text-[var(--green)]">Grafik</a>
        <a href="#kontak" class="nav-link hover:text-[var(--green)]">Kontak</a>
      </nav>

      <!-- Mobile Toggle -->
      <button id="nav-toggle" class="md:hidden p-2 rounded-md focus-ring" aria-expanded="false" aria-controls="mobile-menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden>
          <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden bg-white border-t hidden">
      <div class="px-4 py-3 flex flex-col gap-1">
        <a href="#home" class="px-3 py-2 rounded-md text-gray-800">Beranda</a>
        <a href="#tentang" class="px-3 py-2 rounded-md text-gray-800">Tentang</a>
        <a href="#layanan" class="px-3 py-2 rounded-md text-gray-800">Layanan</a>
        <a href="#grafik" class="px-3 py-2 rounded-md text-gray-800">Grafik</a>
        <a href="#kontak" class="px-3 py-2 rounded-md text-gray-800">Kontak</a>
      </div>
    </div>
  </header>

  <main class="pt-20">

    <!-- HERO -->
    <section id="home" class="min-h-[60vh] flex items-center bg-gradient-to-b from-white to-green-50">
      <div class="max-w-5xl mx-auto px-4 py-12 text-center">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">Membangun Masa Depan <span class="text-[var(--green)]">Hijau</span> & Berkelanjutan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto mb-6">Solusi energi terbarukan, konsultasi lingkungan, dan pengelolaan limbah dengan pendekatan praktis dan lokal.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <a href="#tentang" class="inline-flex items-center justify-center px-5 py-3 rounded-full bg-[var(--green)] text-white text-sm font-semibold shadow-sm hover:opacity-95">Pelajari Lebih Lanjut</a>
          <a href="#kontak" class="inline-flex items-center justify-center px-5 py-3 rounded-full border border-gray-200 text-sm font-semibold bg-white">Hubungi Kami</a>
        </div>
      </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="py-12">
      <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-8 reveal">
          <h2 class="text-2xl font-bold text-gray-900">Tentang PT Sipirok Indah</h2>
          <p class="text-gray-600 mt-2">Kami berkomitmen pada praktik ramah lingkungan yang memberikan manfaat ekonomi lokal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white p-5 rounded-lg shadow-sm reveal">
            <h3 class="font-semibold text-[var(--green)] mb-2">Visi & Misi</h3>
            <p class="text-sm text-gray-700">Mewujudkan pengelolaan sumber daya yang berkelanjutan sambil meningkatkan kesejahteraan masyarakat.</p>
          </div>

          <div class="bg-white p-5 rounded-lg shadow-sm reveal">
            <h3 class="font-semibold text-[var(--green)] mb-2">Nilai Perusahaan</h3>
            <ul class="text-sm text-gray-700 space-y-2">
              <li>• Integritas</li>
              <li>• Inovasi</li>
              <li>• Keberlanjutan</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- LAYANAN -->
    <section id="layanan" class="py-12 bg-white">
      <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-8 reveal">
          <h2 class="text-2xl font-bold text-gray-900">Layanan Kami</h2>
          <p class="text-gray-600 mt-2">Solusi praktis yang siap diimplementasikan untuk bisnis dan komunitas.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Card 1 -->
          <article class="p-4 bg-gray-50 rounded-lg shadow-sm reveal">
            <div class="flex items-center gap-3 mb-3">
              <!-- inline SVG icon to avoid extra dependency -->
              <svg class="w-10 h-10 text-[var(--green)]" viewBox="0 0 24 24" fill="none" aria-hidden>
                <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
              </svg>
              <h3 class="font-semibold">Energi Terbarukan</h3>
            </div>
            <p class="text-sm text-gray-700">Pemasangan panel surya, sistem mikrohidro, dan konsultasi efisiensi energi.</p>
          </article>

          <!-- Card 2 -->
          <article class="p-4 bg-gray-50 rounded-lg shadow-sm reveal">
            <div class="flex items-center gap-3 mb-3">
              <svg class="w-10 h-10 text-[var(--green)]" viewBox="0 0 24 24" fill="none" aria-hidden>
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"></circle>
              </svg>
              <h3 class="font-semibold">Konsultasi Lingkungan</h3>
            </div>
            <p class="text-sm text-gray-700">Audit lingkungan, strategi keberlanjutan, dan pelatihan SDM.</p>
          </article>

          <!-- Card 3 -->
          <article class="p-4 bg-gray-50 rounded-lg shadow-sm reveal">
            <div class="flex items-center gap-3 mb-3">
              <svg class="w-10 h-10 text-[var(--green)]" viewBox="0 0 24 24" fill="none" aria-hidden>
                <rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.5"></rect>
              </svg>
              <h3 class="font-semibold">Pengelolaan Limbah</h3>
            </div>
            <p class="text-sm text-gray-700">Solusi pengolahan limbah industri dan program daur ulang terpadu.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- GRAFIK -->
    <section id="grafik" class="py-12">
      <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6 reveal">
          <h2 class="text-2xl font-bold">Grafik Pengumpulan Sawit per Hari</h2>
          <p class="text-gray-600 mt-1">Data contoh untuk ilustrasi monitoring produksi harian.</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm reveal">
          <div class="chart-h">
            <canvas id="grafikSawit" class="w-full h-full"></canvas>
          </div>
        </div>
      </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak" class="py-12 bg-gradient-to-b from-green-50 to-white">
      <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white p-6 rounded-lg shadow-sm reveal">
            <h3 class="text-lg font-semibold text-[var(--green)] mb-3">Informasi Kontak</h3>
            <p class="text-sm text-gray-700">Jl. Hijau Lestari No.123, Sipirok — +62 812 3456 7890 — info@ptsipirokindah.com</p>

            <div class="mt-4 flex gap-3">
              <a aria-label="facebook" href="#" class="p-2 rounded bg-gray-100">FB</a>
              <a aria-label="instagram" href="#" class="p-2 rounded bg-gray-100">IG</a>
              <a aria-label="linkedin" href="#" class="p-2 rounded bg-gray-100">IN</a>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm reveal">
            <h3 class="text-lg font-semibold text-[var(--green)] mb-3">Kirim Pesan</h3>
            <form id="contact-form" class="space-y-3" novalidate>
              <input name="name" type="text" placeholder="Nama" class="w-full px-3 py-2 border rounded focus-ring" required>
              <input name="email" type="email" placeholder="Email" class="w-full px-3 py-2 border rounded focus-ring" required>
              <textarea name="message" rows="4" placeholder="Pesan" class="w-full px-3 py-2 border rounded focus-ring" required></textarea>
              <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[var(--green)] text-white px-4 py-2 rounded">Kirim Pesan</button>
              <p id="form-msg" class="text-sm text-green-700 hidden">Pesan terkirim. Terima kasih!</p>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-200 py-6">
    <div class="max-w-5xl mx-auto px-4 text-sm text-center">
      <p>© <span id="year"></span> PT Sipirok Indah — Semua hak dilindungi.</p>
    </div>
  </footer>

  <script>
    // Small helper: toggle mobile menu
    (function(){
      const btn = document.getElementById('nav-toggle');
      const menu = document.getElementById('mobile-menu');
      btn && btn.addEventListener('click', ()=>{
        const open = menu.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', !open);
      });

      // Smooth scroll with header offset
      document.querySelectorAll('a[href^="#"]').forEach(a=>{
        a.addEventListener('click', function(e){
          const target = document.querySelector(this.getAttribute('href'));
          if(target){
            e.preventDefault();
            const headerHeight = document.querySelector('header').offsetHeight || 64;
            const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 8;
            window.scrollTo({top, behavior:'smooth'});
            // close mobile menu after click
            if(!menu.classList.contains('hidden')) menu.classList.add('hidden');
          }
        });
      });

      // simple reveal on scroll (lightweight)
      const reveals = document.querySelectorAll('.reveal');
      const onScroll = ()=>{
        reveals.forEach(r=>{
          const rect = r.getBoundingClientRect();
          if(rect.top < window.innerHeight - 60) r.classList.add('visible');
        });
      };
      window.addEventListener('scroll', onScroll, {passive:true});
      window.addEventListener('load', onScroll);

      // header compact on scroll
      const header = document.getElementById('site-header');
      window.addEventListener('scroll', ()=>{
        if(window.scrollY > 80) header.classList.add('compact'); else header.classList.remove('compact');
      }, {passive:true});

      // contact form simple fake submit
      const form = document.getElementById('contact-form');
      const msg = document.getElementById('form-msg');
      if(form){
        form.addEventListener('submit', function(e){
          e.preventDefault();
          // minimal validation
          const fd = new FormData(form);
          if(!fd.get('name') || !fd.get('email') || !fd.get('message')){
            alert('Lengkapi semua field');
            return;
          }
          // simulate submit
          msg.classList.remove('hidden');
          form.reset();
          setTimeout(()=>msg.classList.add('hidden'), 4000);
        });
      }

      // set current year
      document.getElementById('year').textContent = new Date().getFullYear();
    })();

    // Chart init: only when Chart is loaded and canvas exists
    window.addEventListener('load', ()=>{
      const canvas = document.getElementById('grafikSawit');
      if(!canvas || !window.Chart) return;
      const ctx = canvas.getContext('2d');

      const gradient = ctx.createLinearGradient(0,0,0,320);
      gradient.addColorStop(0,'rgba(22,163,74,0.85)');
      gradient.addColorStop(1,'rgba(187,247,208,0.6)');

      new Chart(ctx, {
        type:'bar',
        data:{
          labels:['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
          datasets:[{label:'Jumlah Sawit (ton)', data:[120,150,180,140,200,170,160], backgroundColor:gradient, borderColor:'#14532d', borderWidth:1, borderRadius:6}]
        },
        options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,ticks:{color:'#14532d'}} , x:{ticks:{color:'#14532d'}}},plugins:{legend:{display:false},tooltip:{backgroundColor:'#14532d',titleColor:'#fff',bodyColor:'#fff'}},animation:{duration:800}}
      });
    });
  </script>
</body>
</html>