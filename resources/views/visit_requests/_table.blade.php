<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Aksi</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($requests as $request)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $request->user->profile->level->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->user->profile->department->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->destination }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($request->from_date)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($request->to_date)->isoFormat('D MMM YYYY') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($request->status->name == 'Approved') bg-green-100 text-green-800
                            @elseif($request->status->name == 'Rejected') bg-red-100 text-red-800
                            @elseif($request->status->name == 'Cancelled') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $request->status->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button 
                        type="button" 
                        class="text-indigo-600 hover:text-indigo-900"
                        x-on:click="openDetailModal(JSON.parse(atob('{{ base64_encode(json_encode($request->load(['user.profile.department', 'status']))) }}')))"
                    >
                        Lihat Detail
                    </button>
                         @if ($request->status->name === 'Pending')
                                @can('approve', $request)
                                    
                                    <form action="{{ route('requests.approve', $request) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900">Setujui</button>
                                    </form>

                                    <form id="reject-form-{{ $request->id }}" action="{{ route('requests.reject', $request) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                        onclick="confirmAction(
                                            'reject-form-{{ $request->id }}', 
                                            'Anda Yakin?', 
                                            'Anda akan menolak permintaan ini. Aksi ini tidak bisa diurungkan.'
                                        )" 
                                        class="text-red-600 hover:text-red-900">
                                            Tolak
                                        </button>
                                    </form>
                                    
                                @endcan
                            @endif

                         @if(Auth::id() === $request->user_id && $request->status->name === 'Pending')

                            <form id="cancel-form-{{ $request->id }}" action="{{ route('requests.cancel', $request) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="button" 
                                        onclick="confirmAction(
                                            'cancel-form-{{ $request->id }}', 
                                            'Batalkan Permintaan?', 
                                            'Permintaan yang sudah dibatalkan tidak bisa dikembalikan.'
                                        )" 
                                        class="ml-4 text-red-600 hover:text-red-900">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data permintaan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>