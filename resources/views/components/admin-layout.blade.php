<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Satoria VMS - Admin</title>
        <link rel="icon" href="{{ asset('images/logo-satoria.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div>
            @include('layouts.partials.admin-sidebar')
            <div class="p-4 sm:ml-64">
                <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
                    @if (isset($header))
                        <header class="mb-6">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </div>
        @livewireScripts
        @stack('scripts')
        <x-toast-notification />
        <x-confirmation-modal />
    </body>
    </html>