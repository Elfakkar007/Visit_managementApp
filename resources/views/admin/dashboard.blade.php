<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $totalUsers }}</h5>
                <p class="font-normal text-gray-700">Total Pengguna</p>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $pendingRequests }}</h5>
                <p class="font-normal text-gray-700">Request Pending</p>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $guestsCheckedIn }}</h5>
                <p class="font-normal text-gray-700">Tamu Sedang Check-in</p>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $guestsToday }}</h5>
                <p class="font-normal text-gray-700">Total Tamu Hari Ini</p>
            </div>
        </div>

        <!-- Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">Request Perjalanan Dinas ({{ date('Y') }})</h5>
                <canvas id="requestsChart"></canvas>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="text-lg font-semibold text-gray-900 mb-4">Kunjungan Tamu (7 Hari Terakhir)</h5>
                <canvas id="guestsChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Pastikan Chart.js di-load. Jika sudah ada di layout utama, baris ini bisa dihapus. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Grafik Request Bulanan
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            new Chart(requestsCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah Request',
                        data: @json($requestsChartData),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });

            // Grafik Kunjungan Tamu Mingguan
            const guestsCtx = document.getElementById('guestsChart').getContext('2d');
            new Chart(guestsCtx, {
                type: 'line',
                data: {
                    labels: @json($guestChartLabels),
                    datasets: [{
                        label: 'Jumlah Tamu',
                        data: @json($guestChartData),
                        fill: true,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        tension: 0.3
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
