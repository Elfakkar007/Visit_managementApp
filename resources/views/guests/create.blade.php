<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Kunjungan Tamu</title>
    <link rel="icon" href="{{ asset('images/logo-satoria.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gradient-to-br from-red-100 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white p-8 rounded-2xl shadow-2xl border border-gray-100">
            <h1 class="text-2xl font-bold text-center mb-6 text-red-700">Formulir Kunjungan Tamu</h1>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-pulse" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('guest.store') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false, ktpPreview: null }" @submit="loading = true">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 transition"
                        placeholder="Masukkan nama lengkap">
                </div>
                <div class="mb-4">
                    <label for="company" class="block text-sm font-semibold text-gray-700 mb-1">Asal Perusahaan/Instansi</label>
                    <input type="text" name="company" value="{{ old('company') }}" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 transition"
                        placeholder="Nama perusahaan/instansi">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required pattern="[0-9+ ]*"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 transition"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div class="mb-6" x-data="{ fileName: '', preview: null }">
                    <label for="ktp_photo" class="block text-sm font-semibold text-gray-700 mb-1">Upload Foto KTP</label>
                    <input type="file" name="ktp_photo" id="ktp_photo" required accept="image/png, image/jpeg"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                        @change="
                            fileName = $event.target.files[0]?.name;
                            if ($event.target.files[0]) {
                                const reader = new FileReader();
                                reader.onload = e => preview = e.target.result;
                                reader.readAsDataURL($event.target.files[0]);
                            } else {
                                preview = null;
                            }
                        ">
                    <template x-if="fileName">
                        <p class="mt-1 text-xs text-gray-500">File: <span x-text="fileName"></span></p>
                    </template>
                    <template x-if="preview">
                        <img :src="preview" alt="Preview KTP" class="mt-2 rounded shadow max-h-32 mx-auto border">
                    </template>
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-semibold flex items-center justify-center transition"
                        :disabled="loading"
                        x-bind:class="loading ? 'opacity-60 cursor-not-allowed' : ''">
                        <svg x-show="loading" class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span x-text="loading ? 'Memproses...' : 'Dapatkan Kode QR'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>