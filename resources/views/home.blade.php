<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800">Ini Halaman Homepage</h1>
    <a href="{{ url('/') }}" class="mt-6 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Kembali ke Main Gate</a>
</body>
</html>
