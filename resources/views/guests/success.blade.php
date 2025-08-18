<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Satoria Group</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-xl py-8 px-4">
        
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="mb-4">
                {{-- Icon centang hijau --}}
                <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Berhasil!</h1>
            <p class="text-gray-600 mt-2">Terima kasih, {{ $visit->guest->name }}.</p>
            <p class="text-gray-600">Silakan tunjukkan QR Code di bawah ini kepada resepsionis untuk proses check-in.</p>

            <div class="mt-8 mb-4">
                {{-- Generate QR Code dengan library. Isi dari QR Code adalah UUID unik dari visit. --}}
                {!! QrCode::size(250)->generate($visit->uuid) !!}
            </div>

            <p class="text-sm text-gray-500">Anda dapat melakukan screenshot halaman ini.</p>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Satoria Group. All rights reserved.</p>
        </div>

    </div>
</body>
</html>