<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Permintaan Kunjungan Dinas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Form --}}
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="destination" :value="__('Tujuan Kunjungan')" />
                            <x-text-input id="destination" class="block mt-1 w-full" type="text" name="destination" :value="old('destination')" required autofocus />
                            <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="purpose" :value="__('Keperluan')" />
                            <textarea id="purpose" name="purpose" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('purpose') }}</textarea>
                            <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                             <div>
                                <x-input-label for="from_date" :value="__('Tanggal Mulai')" />
                                <x-text-input id="from_date" class="block mt-1 w-full" type="date" name="from_date" :value="old('from_date')" required />
                                <x-input-error :messages="$errors->get('from_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="to_date" :value="__('Tanggal Selesai')" />
                                <x-text-input id="to_date" class="block mt-1 w-full" type="date" name="to_date" :value="old('to_date')" required />
                                <x-input-error :messages="$errors->get('to_date')" class="mt-2" />
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('requests.my') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Ajukan Permintaan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>