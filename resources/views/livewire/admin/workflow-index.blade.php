<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Level Pengaju</th>
                    <th scope="col" class="px-6 py-3">Subsidiary Pengaju</th>
                    <th scope="col" class="px-6 py-3">Level Penyetuju</th>
                    <th scope="col" class="px-6 py-3">Scope</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workflows as $workflow)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $workflow->requesterLevel->name }}</td>
                        <td class="px-6 py-4">{{ $workflow->requesterSubsidiary->name ?? 'Semua' }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $workflow->approverLevel->name }}</td>
                        <td class="px-6 py-4">{{ $workflow->scope }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.workflows.edit', $workflow->id) }}" class="px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">Belum ada aturan yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $workflows->links() }}
    </div>
</div>