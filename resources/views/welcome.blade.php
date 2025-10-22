<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Gate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="text-center">
        <!-- Logo besar -->
        <div class="mb-10">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzBngVaz7tSw2CaQXVNqw7VJu6Sz99M5U2PQ&s"
            alt="Logo Laravel"
            class="mx-auto w-40 h-40">

            <h1 class="text-3xl font-bold mt-4 text-gray-800">Selamat Datang</h1>
        </div>

        <!-- Tombol navigasi -->
        <div class="space-x-4">
            <a href="{{ url('/home') }}"
               class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
               Homepage
            </a>

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
               Login 
            </a>
        </div>
    </div>

</body>
</html>
