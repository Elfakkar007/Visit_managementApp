{{-- Pastikan ini menggunakan layout utama Anda, misal <x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Scanner Check-in / Check-out Tamu
        </h2>
    </x-slot>

    {{-- Modal untuk Form Check-in --}}
    <div id="checkInModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full z-50" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 text-center">Konfirmasi Check-in</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-600">Tamu: <strong id="modalGuestName"></strong></p>
                <form id="checkInForm" class="mt-4">
                    @csrf
                    <input type="hidden" id="modalUuid" name="uuid">
                    <label for="visit_destination" class="block text-sm font-medium text-gray-700">Tujuan Kunjungan (Nama / Dept)</label>
                    <input type="text" name="visit_destination" id="visit_destination" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3" required>
                    <div class="mt-6 text-right">
                         <button type="submit" id="submitCheckIn" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">Submit Check-in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-center text-gray-600 mb-4">Arahkan kamera ke QR Code tamu.</p>
                <div id="qr-reader" class="w-full"></div>
                <div id="scan-result" class="mt-4 text-center font-semibold"></div>
            </div>
        </div>
    </div>

   @push('scripts')
    {{-- Library untuk QR Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resultContainer = document.getElementById('scan-result');
            const modal = document.getElementById('checkInModal');
            const checkInForm = document.getElementById('checkInForm');
            const submitCheckInBtn = document.getElementById('submitCheckIn');
            let isProcessing = false;

            function onScanSuccess(decodedText, decodedResult) {
                if (isProcessing) return;
                isProcessing = true;
                
                html5QrcodeScanner.clear();
                resultContainer.innerHTML = `<span class="text-blue-600">Memproses kode...</span>`;

                fetch(`/receptionist/get-visit-status/${decodedText}`)
                    .then(response => response.json())
                    .then(data => {
                        handleScanResponse(data);
                    })
                    .catch(error => {
                        resultContainer.innerHTML = `<span class="text-red-600">Error: ${error}</span>`;
                        setTimeout(() => location.reload(), 2500);
                    });
            }

            function handleScanResponse(data) {
                resultContainer.innerHTML = `<span class="text-green-600">${data.message || ''}</span>`;
                switch (data.status) {
                    case 'needs_check_in':
                        document.getElementById('modalGuestName').textContent = data.guest_name;
                        document.getElementById('modalUuid').value = data.uuid;
                        modal.style.display = 'block';
                        document.getElementById('visit_destination').focus();
                        isProcessing = false; // Allow new interaction
                        break;
                    case 'checked_out_success':
                    case 'already_checked_out':
                    case 'error':
                        setTimeout(() => location.reload(), 2500);
                        break;
                }
            }

            submitCheckInBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const formData = new FormData(checkInForm);
                submitCheckInBtn.disabled = true;
                submitCheckInBtn.textContent = 'Memproses...';

                fetch('{{ route("receptionist.performCheckIn") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'check_in_success') {
                        modal.style.display = 'none';
                        resultContainer.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                        submitCheckInBtn.disabled = false;
                        submitCheckInBtn.textContent = 'Submit Check-in';
                    }
                });
            });

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", { fps: 10, qrbox: {width: 250, height: 250} });
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
    @endpush
</x-app-layout>