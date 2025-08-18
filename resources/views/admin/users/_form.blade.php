{{-- resources/views/admin/users/_form.blade.php --}}
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Kolom Kiri --}}
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Login</h3>
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
            @if(isset($user))<p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>@endif
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
         <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
    </div>
    {{-- Kolom Kanan --}}
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Profil & Jabatan</h3>
         <div class="mt-4">
            <x-input-label for="subsidiary_id" :value="__('Subsidiary')" />
            <select name="subsidiary_id" id="subsidiary_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($subsidiaries as $subsidiary)
                    <option value="{{ $subsidiary->id }}" @selected(old('subsidiary_id', $user->profile->subsidiary_id ?? '') == $subsidiary->id)>{{ $subsidiary->name }}</option>
                @endforeach
            </select>
        </div>
         <div class="mt-4">
            <x-input-label for="department_id" :value="__('Departemen')" />
            <select name="department_id" id="department_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $user->profile->department_id ?? '') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <x-input-label for="role_id" :value="__('Role Sistem')" />
            <select name="role_id" id="role_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->profile->role_id ?? '') == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <x-input-label for="level_id" :value="__('Level Jabatan')" />
            <select name="level_id" id="level_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(old('level_id', $user->profile->level_id ?? '') == $level->id)>{{ $level->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Nomor Telepon (Opsional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->profile->phone ?? '')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
    </div>
</div>
<div class="flex items-center justify-end mt-6 pt-6 border-t">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <x-primary-button>{{ isset($user) ? 'Perbarui Pengguna' : 'Buat Pengguna' }}</x-primary-button>
</div>