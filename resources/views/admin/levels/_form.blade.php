{{-- resources/views/admin/levels/_form.blade.php --}}
@csrf
<div>
    <x-input-label for="name" :value="__('Nama Level')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $level->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.levels.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <x-primary-button>{{ isset($level) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
</div>