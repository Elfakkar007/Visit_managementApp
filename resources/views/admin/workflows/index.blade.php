<x-admin-layout>
    <x-slot name="header">
        {{-- Header dengan Tombol Aksi di Kanan --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ __('Manajemen Alur Approval') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Atur alur persetujuan berdasarkan level jabatan di sini.
                </p>
            </div>
            <a href="{{ route('admin.workflows.create') }}" class="inline-flex items-center justify-center mt-3 sm:mt-0 px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Aturan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        {{-- Notifikasi Sukses Flowbite --}}
                        <div id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
                            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            <span class="sr-only">Info</span>
                            <div class="ms-3 text-sm font-medium">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-3" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    {{-- Tabel Data Flowbite --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Aturan</th>
                                    <th scope="col" class="px-6 py-3">Cakupan (Scope)</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workflows as $workflow)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-base font-semibold text-gray-900">
                                                    Jika <span class="font-bold text-blue-600">{{ $workflow->requesterLevel->name }}</span> mengajukan,
                                                </div>
                                                <div class="font-normal text-gray-500">
                                                    maka harus disetujui oleh <span class="font-semibold">{{ $workflow->approverLevel->name }}</span>.
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full capitalize 
                                            {{ $workflow->scope == 'department' ? 'bg-purple-100 text-purple-800' : 'bg-sky-100 text-sky-800' }}">
                                            {{ $workflow->scope }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-4">
                                            <a href="{{ route('admin.workflows.edit', $workflow->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.workflows.destroy', $workflow->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus aturan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada aturan alur approval yang dibuat.
                                        <a href="{{ route('admin.workflows.create') }}" class="text-blue-600 hover:underline font-medium">Buat Aturan Baru</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>