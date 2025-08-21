@props([
    'triggerText' => 'Konfirmasi',
    'confirmText' => 'Ya, Lanjutkan',
    'title' => 'Anda Yakin?',
    'method' => 'POST',
    'action' => '#',
    'needsRejectionReason' => false, // <-- Tambahkan props baru
])

<div x-data="{ show: false }" @keydown.escape.window="show = false">
    {{-- Tombol Pemicu --}}
    <button type-="button" @click="show = true" {{ $attributes->merge(['class' => 'text-red-600 hover:text-red-900']) }}>
        {{ $triggerText }}
    </button>

    {{-- Latar Belakang & Konten Modal ... (tidak ada perubahan) ... --}}
    <div x-show="show" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.away="show = false" class="relative bg-white rounded-2xl p-6 text-left ...">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                
                <div class="mt-2 text-sm text-gray-600">
                    {{ $slot }}
                </div>
                
                {{-- Form di dalam Footer --}}
                <form action="{{ $action }}" method="POST">
                    @csrf
                    @if(!in_array(strtoupper($method), ['GET','POST']))
                        @method($method)
                    @endif

                    {{-- === TAMBAHKAN BLOK INI === --}}
                    @if($needsRejectionReason)
                    <div class="mt-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    @endif
                    {{-- === AKHIR BLOK TAMBAHAN === --}}

                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center ...">
                            {{ $confirmText }}
                        </button>
                        <button @click="show = false" type="button" class="mt-3 w-full inline-flex justify-center ...">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>