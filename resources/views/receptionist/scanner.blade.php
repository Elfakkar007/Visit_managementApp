<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scanner Check-in / Check-out Tamu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                         <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-4 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="mb-4 text-gray-600">Arahkan scanner ke QR Code tamu atau masukkan kode unik di bawah ini.</p>

                    <form action="{{ route('receptionist.processScan') }}" method="POST">
                        @csrf
                        <div>
                            <x-input-label for="uuid" :value="__('Kode Unik Tamu (UUID)')" />
                            <x-text-input id="uuid" class="block mt-1 w-full" type="text" name="uuid" required autofocus placeholder="Scan atau ketik manual kode di sini..." />
                            <x-input-error :messages="$errors->get('uuid')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Proses') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
             <div class="mt-4 text-center">
                <a href="{{ route('receptionist.history') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                    Lihat Riwayat Kunjungan Tamu
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
