<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            {{-- Level Pengaju --}}
            <div>
                <label for="requester_level_id" class="block mb-2 text-sm font-medium text-gray-900">Jika Pengaju Memiliki Level</label>
                <select id="requester_level_id" wire:model="requester_level_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Level Pengaju</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
                @error('requester_level_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Subsidiary --}}
            <div>
                <label for="subsidiary_id" class="block mb-2 text-sm font-medium text-gray-900">Dan Pengaju Berasal dari Subsidiary (Opsional)</label>
                <select id="subsidiary_id" wire:model="subsidiary_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Berlaku untuk Semua Subsidiary</option>
                    @foreach($subsidiaries as $subsidiary)
                        <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }}</option>
                    @endforeach
                </select>
                @error('subsidiary_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Level Penyetuju --}}
            <div>
                <label for="approver_level_id" class="block mb-2 text-sm font-medium text-gray-900">Maka Harus Disetujui Oleh Level</label>
                <select id="approver_level_id" wire:model="approver_level_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Level Penyetuju</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
                @error('approver_level_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Scope --}}
            <div>
                <label for="scope" class="block mb-2 text-sm font-medium text-gray-900">Dengan Cakupan (Scope)</label>
                <select id="scope" wire:model="scope" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Scope</option>
                    <option value="department">Departemen yang Sama</option>
                    <option value="subsidiary">Subsidiary yang Sama</option>
                    <option value="cross_subsidiary">Lintas Subsidiary</option>
                </select>
                @error('scope') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('admin.workflows.index') }}" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">Batal</a>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Aturan</button>
            </div>
        </div>
    </form>
</div>