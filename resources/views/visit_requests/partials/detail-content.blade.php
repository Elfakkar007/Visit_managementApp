{{-- Informasi Pemohon --}}
<div class="mb-6">
    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Informasi Pemohon</h4>
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
        <div>
            <dt class="font-medium text-gray-500">Nama Pemohon</dt>
            <dd class="mt-1 text-gray-900">{{ $request->user->name ?? '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500">Subsidiary</dt>
            <dd class="mt-1 text-gray-900">{{ $request->user->profile->subsidiary->name ?? '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500">Departemen</dt>
            <dd class="mt-1 text-gray-900">{{ $request->user->profile->department->name ?? '-' }}</dd>
        </div>
    </dl>
</div>

{{-- Detail Perjalanan --}}
<div class="mb-6">
    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Detail Perjalanan</h4>
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500">Tujuan</dt>
            <dd class="mt-1 text-gray-900">{{ $request->destination ?? '-' }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500">Keperluan</dt>
            <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $request->purpose ?? '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500">Tanggal Mulai</dt>
            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($request->from_date)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500">Tanggal Selesai</dt>
            <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($request->to_date)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</dd>
        </div>
    </dl>
</div>

{{-- Status Approval --}}
<div>
    <h4 class="font-semibold text-gray-700 border-b pb-2 mb-3">Status Approval</h4>
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
        <div>
            <dt class="font-medium text-gray-500">Status Saat Ini</dt>
            <dd class="mt-1 text-gray-900">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($request->status->name == 'Approved') bg-green-100 text-green-800
                    @elseif($request->status->name == 'Rejected') bg-red-100 text-red-800
                    @elseif($request->status->name == 'Cancelled') bg-gray-100 text-gray-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $request->status->name }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500">Diproses oleh (Approver)</dt>
            <dd class="mt-1 text-gray-900">{{ $request->approver->name ?? '-' }}</dd>
        </div>
        @if($request->rejection_reason)
        <div class="sm:col-span-2">
            <dt class="font-medium text-red-600">Catatan dari Approver:</dt>
            <dd class="mt-1 text-sm text-gray-800 bg-red-50 p-3 rounded-md">{{ $request->rejection_reason }}</dd>
        </div>
        @endif
    </dl>
</div>
