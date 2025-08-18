{{-- resources/views/admin/departments/_form.blade.php --}}
@csrf
<div>
    <x-input-label for="name" :value="__('Nama Departemen')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $department->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.departments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <x-primary-button>{{ isset($department) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
</div>