<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <link rel="icon" href="{{ asset('images/logo-satoria.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
            
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Pendaftaran Berhasil!</h2>
            
            <p class="text-gray-600 mb-4">
                Terima kasih, <span class="font-semibold">{{ $visit->guest->name }}</span>.
            </p>

            {{-- ====================================================== --}}
            {{-- BAGIAN BARU UNTUK MENAMPILKAN QR CODE --}}
            {{-- ====================================================== --}}
            <div class="flex justify-center my-6">
                
                {!! $qrCode !!}
            </div>
            {{-- ====================================================== --}}

            <p class="text-gray-700 font-medium mb-2">
                Tunjukkan QR Code ini kepada resepsionis.
            </p>

            <p class="text-xs text-gray-500">
                Anda dapat melakukan screenshot halaman ini.
            </p>

        </div>
    </div>
</body>
</html>
