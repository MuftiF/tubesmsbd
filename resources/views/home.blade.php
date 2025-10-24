<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Sipirok Indah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 text-gray-800 font-sans">

    {{-- Navbar --}}
    <header class="bg-green-700 text-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
            <h1 class="text-2xl font-bold">PT Sipirok Indah</h1>
            <nav class="space-x-6">
                <a href="#home" class="hover:text-green-200 transition">Beranda</a>
                <a href="#tentang" class="hover:text-green-200 transition">Tentang</a>
                <a href="#layanan" class="hover:text-green-200 transition">Layanan</a>
                <a href="#kontak" class="hover:text-green-200 transition">Kontak</a>
            </nav>
        </div>
    </header>

    {{-- Hero Section --}}
    <section id="home" class="flex flex-col items-center justify-center text-center bg-green-600 text-white h-screen px-6">
        <h2 class="text-4xl md:text-5xl font-extrabold mb-4">Membangun Masa Depan yang Hijau dan Berkelanjutan</h2>
        <p class="text-lg md:text-xl max-w-2xl mb-8">
            Kami berkomitmen untuk menciptakan solusi ramah lingkungan dan mendukung pembangunan berkelanjutan di Indonesia.
        </p>
        <a href="#tentang" class="bg-white text-green-700 px-6 py-3 rounded-full font-semibold hover:bg-green-100 transition">
            Pelajari Lebih Lanjut
        </a>
    </section>

    {{-- Tentang Kami --}}
    <section id="tentang" class="max-w-6xl mx-auto py-16 px-6 text-center">
        <h3 class="text-3xl font-bold text-green-700 mb-6">Tentang Kami</h3>
        <p class="text-gray-700 leading-relaxed max-w-3xl mx-auto">
            PT Hijau Lestari adalah perusahaan yang berdedikasi dalam pengembangan energi terbarukan, pengelolaan sumber daya alam, 
            dan inovasi ramah lingkungan. Kami percaya kemajuan ekonomi dapat berjalan selaras dengan pelestarian alam.
        </p>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="bg-white py-16">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h3 class="text-3xl font-bold text-green-700 mb-10">Layanan Kami</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-green-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-green-800">Energi Terbarukan</h4>
                    <p>Menghadirkan solusi energi bersih dari sumber daya matahari dan angin.</p>
                </div>
                <div class="bg-green-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-green-800">Konsultasi Lingkungan</h4>
                    <p>Membantu perusahaan menerapkan praktik bisnis ramah lingkungan.</p>
                </div>
                <div class="bg-green-100 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2 text-green-800">Pengelolaan Limbah</h4>
                    <p>Solusi efektif dan aman untuk pengolahan limbah industri.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontak --}}
    <section id="kontak" class="bg-green-50 py-16 text-center">
        <h3 class="text-3xl font-bold text-green-700 mb-6">Hubungi Kami</h3>
        <p class="text-gray-700 mb-8">Ingin tahu lebih banyak? Kami siap membantu Anda.</p>
        <a href="mailto:info@pthijaulestari.com" 
           class="bg-green-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-green-700 transition">
           Kirim Email
        </a>
    </section>

    {{-- Footer --}}
    <footer class="bg-green-700 text-white text-center py-6 mt-16">
        © {{ date('Y') }} PT Sipirok Indah
    </footer>

</body>
</html>
