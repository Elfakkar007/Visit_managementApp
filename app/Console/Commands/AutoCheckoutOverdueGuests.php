<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GuestVisit;
use Carbon\Carbon;

class AutoCheckoutOverdueGuests extends Command
{
    protected $signature = 'guests:autocheckout';
    protected $description = 'Secara otomatis melakukan check-out untuk tamu yang sudah check-in lebih dari 24 jam';

    public function handle()
    {
        $this->info('Memulai proses auto check-out...');

        $cutOffTime = Carbon::now()->subHours(24);

        $overdueVisits = GuestVisit::where('status', 'checked_in')
                                    ->where('time_in', '<=', $cutOffTime)
                                    ->get();
        
        if ($overdueVisits->isEmpty()) {
            $this->info('Tidak ada tamu yang perlu di-checkout otomatis.');
            return 0;
        }

        foreach ($overdueVisits as $visit) {
            $visit->update([
                'status' => 'checked_out',
                'time_out' => $visit->time_in->addHours(24), // Set waktu checkout 24 jam setelah check-in
                'checked_out_by' => null, // Tandai sebagai checkout oleh sistem
            ]);
            $this->info("Tamu '{$visit->guest->name}' (ID Kunjungan: {$visit->id}) telah di-checkout otomatis.");
        }

        $this->info('Proses auto check-out selesai. Total: ' . $overdueVisits->count() . ' tamu.');
        return 0;
    }
}