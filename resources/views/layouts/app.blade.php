<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Satoria VMS</title>
        <link rel="icon" href="{{ asset('images/logo-satoria.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <div class="flex">
                @include('layouts.partials.app-sidebar')
                <div class="flex-1 sm:ml-64">
                    @include('layouts.navigation')
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
{{-- ... kode lainnya di dalam body ... --}}

        @livewireScripts
        @stack('scripts')

        <!-- {{-- ====================================================== --}}
        {{-- KODE JEMBATAN UNIVERSAL (INI YANG PALING PENTING) --}}
        {{-- ====================================================== --}}
        
        {{-- Jembatan 1: Untuk pesan dari Controller (setelah redirect) --}}
        @if (session('show-toast'))
            @php $toast = session('show-toast'); @endphp
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('show-toast', { 
                        detail: { 
                            type: '{{ $toast['type'] }}', 
                            message: '{{ addslashes($toast['message']) }}'
                        }
                    }));
                });
            </script>
        @endif

        {{-- Jembatan 2: Untuk semua event dari Livewire --}}
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('show-toast', event => {
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: event }));
                });
            });
        </script>
        
        {{-- Komponen Frontend yang Menerima Sinyal --}} -->
        <x-toast-notification />
        <x-confirmation-modal />
        <x-loading-indicator />

    </body>
</html>