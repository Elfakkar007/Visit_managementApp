<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Ganti baris ini --}}
        <title>Satoria VMS</title>
        <link rel="icon" href="{{ asset('images/logo-satoria.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <div class="flex">
                @include('layouts.partials.app-sidebar')

                <div class="flex-1 sm:ml-64">

                    @include('layouts.navigation')

                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white border-b border-gray-200">
                            <div class="w-full mx-auto p-4 sm:p-6">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
        @livewireScripts
         @stack('scripts')
          <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (event) => {
                Toastify({
                    text: event.message,
                    duration: 3000,
                    gravity: "top", 
                    position: "right", 
                    stopOnFocus: true,
                    style: {
                        background: event.type === 'success' ? '#10B981' : '#EF4444',
                    },
                }).showToast();
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
         
            
    </body>
</html>
