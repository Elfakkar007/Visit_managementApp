<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="relative flex flex-col w-full max-w-4xl m-6 bg-white shadow-2xl rounded-2xl md:flex-row">
            
            <!-- Kolom Kiri (Form Login) -->
            <div class="w-full md:w-1/2 flex flex-col justify-center p-8 md:p-12">
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
                <p class="mt-2 text-gray-600">Silakan masuk untuk mengakses aplikasi.</p>

                <form class="mt-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Email anda" required autofocus autocomplete="username" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="password anda">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="eye-slash-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <script>
                        function togglePasswordVisibility() {
                            const passwordInput = document.getElementById('password');
                            const eyeIcon = document.getElementById('eye-icon');
                            const eyeSlashIcon = document.getElementById('eye-slash-icon');
                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                eyeIcon.classList.add('hidden');
                                eyeSlashIcon.classList.remove('hidden');
                            } else {
                                passwordInput.type = 'password';
                                eyeIcon.classList.remove('hidden');
                                eyeSlashIcon.classList.add('hidden');
                            }
                        }
                    </script>

                    <!-- Remember Me -->
                    <div class="mt-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">Simpan Password</label>
                        </div>
                    </div>

                    <!-- Tombol Login -->
                    <div class="mt-6">
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Log In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan (Branding) -->
            <div class="relative hidden md:block md:w-1/2">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1935&auto=format&fit=crop" alt="Branding Image" class="w-full h-full object-cover rounded-r-2xl">
                <div class="absolute inset-0 bg-blue-900 bg-opacity-60 rounded-r-2xl flex flex-col items-center justify-center text-white p-8 text-center">
                    <h2 class="text-3xl font-bold">Visit Management System</h2>
                    <p class="mt-4 text-blue-100">Sistem terpusat untuk mengelola kunjungan kerja dan data tamu di lingkungan Satoria Group.</p>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
