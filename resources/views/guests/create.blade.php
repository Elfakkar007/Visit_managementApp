{{-- Anda bisa mengganti ini dengan layout utama Anda --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Kunjungan Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 max-w-lg">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center mb-6">Formulir Kunjungan Tamu</h1>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('guest.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mt-4">
                    <label for="company" class="block text-sm font-medium text-gray-700">Asal Perusahaan/Instansi</label>
                    <input type="text" name="company" value="{{ old('company') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mt-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mt-4">
                    <label for="ktp_photo" class="block text-sm font-medium text-gray-700">Upload Foto KTP</label>
                    <input type="file" name="ktp_photo" required class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                     <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                        Dapatkan Kode QR
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>