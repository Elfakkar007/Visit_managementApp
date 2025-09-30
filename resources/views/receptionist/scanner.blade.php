@php
    $layout = Auth::user()->hasRole('Admin') ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
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
                <form id="checkInForm" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" id="modalUuid" name="uuid">
                    
                 
                    <div>
                        <label for="destination_department_id" class="block text-sm font-medium text-gray-700">Departemen Tujuan</label>
                        <select name="destination_department_id" id="destination_department_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3" required>
                            <option value="">Pilih Departemen...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="destination_person_name" class="block text-sm font-medium text-gray-700">Nama Orang yang Dituju</label>
                        <input type="text" name="destination_person_name" id="destination_person_name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3" required>
                    </div>

                    <div>
                        <label for="notification_duration_hours" class="block text-sm font-medium text-gray-700">Ingatkan Setelah (Jam)</label>
                        <input type="number" name="notification_duration_hours" id="notification_duration_hours" value="8" min="1" max="24" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3" required>
                    </div>
                                

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
        // Fungsi helper untuk memancarkan toast
        function dispatchToast(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: message, type: type }
            }));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const resultContainer = document.getElementById('scan-result');
            const modal = document.getElementById('checkInModal');
            const checkInForm = document.getElementById('checkInForm');
            const submitCheckInBtn = document.getElementById('submitCheckIn');
            let isProcessing = false;
            
            // Buat instance scanner di luar agar bisa diakses fungsi lain
            var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: {width: 250, height: 250} });

            // --- FUNGSI BARU UNTUK MERESET SCANNER ---
            function resetScanner() {
                isProcessing = false;
                // Cek status scanner sebelum render ulang untuk menghindari error
                if (html5QrcodeScanner.getState() !== 1) { // 1 = SCANNING
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                }
            }
            
            function onScanSuccess(decodedText, decodedResult) {
                if (isProcessing) return;
                isProcessing = true;
                
                // Hentikan pemindaian sementara
                html5QrcodeScanner.pause();

                fetch(`/receptionist/get-visit-status/${decodedText}`)
                    .then(response => {
                        if (!response.ok) {
                            // Jika response server error (cth: 404, 500), lempar error
                            throw new Error('Server_Error');
                        }
                        return response.json();
                    })
                    .then(data => {
                        handleScanResponse(data);
                    })
                    .catch(error => {
                        dispatchToast('Terjadi kesalahan koneksi atau QR tidak valid.', 'error');
                        // Jeda sejenak lalu reset scanner
                        setTimeout(resetScanner, 2500);
                    });
            }

            // Fungsi ini bisa dikosongkan jika tidak ada perlakuan khusus
            function onScanFailure(error) {
                // console.warn(`QR error = ${error}`);
            }

            function handleScanResponse(data) {
                const message = data.message;
                const type = data.status.includes('success') ? 'success' : 'error';
                
                if (message) {
                    dispatchToast(message, type);
                }

                switch (data.status) {
                    case 'needs_check_in':
                        document.getElementById('modalGuestName').textContent = data.guest_name;
                        document.getElementById('modalUuid').value = data.uuid;
                        modal.style.display = 'block';
                        document.getElementById('destination_department_id').focus(); // Fokus ke field baru
                        // isProcessing akan direset saat modal ditutup atau form disubmit
                        break;
                    
                    // KONDISI YANG SEBELUMNYA ME-REFRESH HALAMAN
                    case 'checked_out_success':
                    case 'already_checked_out':
                    case 'error':
                        // Sekarang hanya mereset scanner setelah 2.5 detik
                        setTimeout(resetScanner, 2500);
                        break;
                }
            }

            checkInForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(checkInForm);
                submitCheckInBtn.disabled = true;
                submitCheckInBtn.textContent = 'Memproses...';

                fetch('{{ route("receptionist.performCheckIn") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'check_in_success') {
                        modal.style.display = 'none';
                        dispatchToast(data.message, 'success');
                        setTimeout(() => location.reload(), 2000); // Reload setelah sukses check-in
                    }
                })
                .catch(error => {
                    dispatchToast(error.message || 'Gagal melakukan check-in.', 'error');
                    submitCheckInBtn.disabled = false;
                    submitCheckInBtn.textContent = 'Submit Check-in';
                    // Reset scanner jika gagal submit
                    resetScanner();
                });
            });

            // Mulai scanning saat halaman dimuat
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
@endpush
   
</x-dynamic-component>