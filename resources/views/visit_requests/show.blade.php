<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Permintaan Kunjungan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-lg font-bold">Informasi Pemohon</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visitRequest->user->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Departemen</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visitRequest->user->profile->department->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Jabatan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visitRequest->user->profile->level->name }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-b pb-4 mb-4">
                         <h3 class="text-lg font-bold">Informasi Kunjungan</h3>
                         <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Tujuan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visitRequest->destination }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Keperluan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $visitRequest->purpose }}</dd>
                            </div>
                             <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($visitRequest->from_date)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($visitRequest->to_date)->isoFormat('D MMM YYYY') }}</dd>
                            </div>
                         </dl>
                    </div>

                     <div>
                        <h3 class="text-lg font-bold">Status Permintaan</h3>
                        <div class="mt-2">
                             <p class="mt-1 text-sm text-gray-900">
                                Saat ini status permintaan adalah 
                                <span class="font-bold px-2 inline-flex text-xs leading-5 rounded-full
                                    @if($visitRequest->status->name == 'Approved') bg-green-100 text-green-800
                                    @elseif($visitRequest->status->name == 'Rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $visitRequest->status->name }}
                                </span>
                            </p>
                            @if ($visitRequest->status->name == 'Approved')
                                <p class="text-sm text-gray-500 mt-1">Disetujui oleh: {{ $visitRequest->approver->name ?? '-' }} pada {{ $visitRequest->approved_at->format('d M Y, H:i') }}</p>
                            @elseif ($visitRequest->status->name == 'Rejected')
                                <p class="text-sm text-gray-500 mt-1">Ditolak karena: {{ $visitRequest->rejection_reason }}</p>
                            @endif
                        </div>
                    </div>


                    @can('approve', $visitRequest)
                        @if ($visitRequest->status->name == 'Pending')
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-bold text-gray-800">Tindakan Persetujuan</h3>
                            <div class="mt-4 flex items-center space-x-4">
                                <form action="{{ route('requests.approve', $visitRequest) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-primary-button>Setujui</x-primary-button>
                                </form>
                                <form action="{{ route('requests.reject', $visitRequest) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center space-x-2">
                                        <x-danger-button>Tolak</x-danger-button>
                                        <x-text-input type="text" name="rejection_reason" placeholder="Alasan penolakan..." class="w-full md:w-64"/>
                                    </div>
                                     <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                                </form>
                            </div>
                        </div>
                        @endif
                    @endcan
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>