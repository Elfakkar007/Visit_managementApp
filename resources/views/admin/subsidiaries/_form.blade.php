{{-- resources/views/admin/subsidiaries/_form.blade.php --}}
@csrf
<div>
    <x-input-label for="name" :value="__('Nama Subsidiary')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $subsidiary->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.subsidiaries.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <x-primary-button>{{ isset($subsidiary) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
</div>