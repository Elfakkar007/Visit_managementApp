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

    <!-- html5-qrcode -->
    <!-- <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-center text-gray-600 mb-4">Arahkan kamera ke QR Code tamu.</p>
                <div id="qr-reader" class="w-full"></div>
                <div id="scan-result" class="mt-4 text-center font-semibold"></div>
            </div>
        </div>
    </div> -->

        {{-- TAMBAHKAN KODE INI --}}
    <div class="w-full max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">Scan QR Code Tamu</h2>
        <p class="text-center text-gray-500 mb-6">Arahkan USB scanner ke QR code, atau ketik manual kode unik di bawah ini dan tekan Enter.</p>
        
        {{-- Form untuk menangkap input dari scanner --}}
        <form id="scanner-form">
            <label for="qr-code-input" class="sr-only">Kode QR</label>
            <input 
                type="text" 
                id="qr-code-input"
                class="block w-full px-4 py-3 text-lg text-center border-gray-300 rounded-md shadow-sm focus:ring-satoria-light focus:border-satoria-light" 
                placeholder="Menunggu scan..."
                autofocus {{-- Sangat Penting! Agar input ini selalu aktif --}}
            >
        </form>
        <div class="text-center mt-4">
            <i class="fas fa-barcode fa-3x text-gray-300"></i>
        </div>
    </div>
@push('scripts')
<script>
    // Fungsi helper untuk menampilkan notifikasi toast
    function dispatchToast(message, type = 'success') {
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: { message: message, type: type }
        }));
    }

    document.addEventListener('DOMContentLoaded', function () {
        const scannerForm = document.getElementById('scanner-form');
        const qrCodeInput = document.getElementById('qr-code-input');
        const modal = document.getElementById('checkInModal');
        const checkInForm = document.getElementById('checkInForm');
        const submitCheckInBtn = document.getElementById('submitCheckIn');

        // Fungsi yang hilang: untuk menghubungi server dan memeriksa status QR
        function getVisitStatus(uuid) {
            fetch(`/receptionist/get-visit-status/${uuid}`)
                .then(response => {
                    if (!response.ok) throw new Error('Kode QR tidak valid atau terjadi error server.');
                    return response.json();
                })
                .then(data => handleScanResponse(data))
                .catch(error => {
                    dispatchToast(error.message, 'error');
                    resetScannerInput(); // Reset input jika error
                });
        }

        // Fungsi untuk menangani respons dari server
        function handleScanResponse(data) {
            const message = data.message;
            const type = (data.status && data.status.includes('success')) ? 'success' : 'error';
            
            if (message) {
                dispatchToast(message, type);
            }

            if (data.status === 'needs_check_in') {
                // Mengisi data ke modal dan menampilkannya
                document.getElementById('modalGuestName').textContent = data.guest_name;
                document.getElementById('modalUuid').value = data.uuid;
                modal.style.display = 'block';
                document.getElementById('destination_department_id').focus();
            }
        }
        
        // Fungsi untuk mereset input scanner
        function resetScannerInput() {
            qrCodeInput.value = '';
            qrCodeInput.focus();
        }

        // Event listener untuk form scanner (ini sudah benar)
        scannerForm.addEventListener('submit', function (event) {
            event.preventDefault(); 
            const decodedText = qrCodeInput.value;
            if (decodedText) {
                // Panggil fungsi yang tadi kita buat
                getVisitStatus(decodedText);
            }
            // Kosongkan input setelah submit
            qrCodeInput.value = '';
        });

        // Event listener untuk form check-in di dalam modal
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
            .then(response => response.json())
            .then(data => {
                if (data.status === 'check_in_success') {
                    modal.style.display = 'none';
                    dispatchToast(data.message, 'success');
                    resetScannerInput();
                } else {
                    throw new Error(data.message || 'Gagal melakukan check-in.');
                }
            })
            .catch(error => {
                dispatchToast(error.message, 'error');
            })
            .finally(() => {
                submitCheckInBtn.disabled = false;
                submitCheckInBtn.textContent = 'Submit Check-in';
            });
        });

        // Event listener untuk memastikan input selalu fokus
        document.body.addEventListener('click', function(e) {
            // Hindari re-focus jika yang diklik ada di dalam modal
            if (!modal.contains(e.target) && modal.style.display === 'none') {
                qrCodeInput.focus();
            }
        });

        // Menutup modal jika area luar diklik
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                resetScannerInput();
            }
        }
    });
</script>
@endpush
   
</x-dynamic-component>