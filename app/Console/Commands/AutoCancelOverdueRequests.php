<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VisitRequest;
use App\Models\Status;
use App\Jobs\SendVisitRequestNotification;
use App\Notifications\VisitRequestStatusUpdated;

class AutoCancelOverdueRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requests:cancel-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan request yang sudah melewati tanggal mulai tapi belum diapprove';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingStatusId = Status::where('name', 'Pending')->first()->id;
        $cancelledStatusId = Status::where('name', 'Cancelled')->first()->id;

        // Eager load relasi 'user' untuk efisiensi
        $overdueRequests = VisitRequest::with('user')
            ->where('status_id', $pendingStatusId)
            ->whereDate('from_date', '<', now())
            ->get();

        if ($overdueRequests->isEmpty()) {
            $this->info('Tidak ada request yang perlu dibatalkan.');
            return;
        }

        foreach ($overdueRequests as $request) {
            $request->update(['status_id' => $cancelledStatusId]);
            
            // Kirim notifikasi ke pembuat request
            if ($request->user) {
                SendVisitRequestNotification::dispatch(
                    $request->user, 
                    new VisitRequestStatusUpdated($request->fresh())
                );
            }

            $this->info("Request ID: {$request->id} telah dibatalkan dan notifikasi dikirim.");
        }
        
        $this->info('Proses pembatalan otomatis selesai.');
    }
}