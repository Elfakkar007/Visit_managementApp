<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Kunjungan Tamu - Satoria Group</title>
    {{-- Menggunakan Vite untuk Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-xl py-8 px-4">
        
        <div class="text-center mb-8">
            {{-- Anda bisa meletakkan logo perusahaan di sini --}}
            {{-- <img src="/logo.png" alt="Satoria Group Logo" class="mx-auto h-12 w-auto"> --}}
            <h1 class="text-3xl font-bold text-gray-800 mt-4">Formulir Kunjungan Tamu</h1>
            <p class="text-gray-600">Selamat datang di Satoria Group. Mohon isi data di bawah ini.</p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <form action="{{ route('guest.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold mb-2">Nomor Telepon</label>
                <input type="tel" name="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            
            <div class="mb-4">
                <label for="company" class="block text-gray-700 font-bold mb-2">Asal Perusahaan</label>
                <input type="text" name="company" id="company" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            {{-- === PERUBAHAN DI SINI === --}}
            <div class="mb-6">
                <label for="visit_destination" class="block text-gray-700 font-bold mb-2">Tujuan Kunjungan (Nama Orang / Departemen)</label>
                <input type="text" name="visit_destination" id="visit_destination" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            
            {{-- Textarea Keperluan/Purpose Dihapus --}}

            <div class="flex items-center justify-center">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full w-full">
                    Dapatkan Kode QR Kunjungan
                </button>
            </div>
        </form>
        </div>
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Satoria Group. All rights reserved.</p>
        </div>
    </div>
</body>
</html>