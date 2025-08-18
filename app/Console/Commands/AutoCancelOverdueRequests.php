<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCancelOverdueRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    

    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Execute the console command.
     */
    protected $signature = 'requests:cancel-overdue';
    protected $description = 'Membatalkan request yang sudah melewati tanggal mulai tapi belum diapprove';

    public function handle()
    {
        $pendingStatusId = \App\Models\Status::where('name', 'Pending')->first()->id;
        $cancelledStatusId = \App\Models\Status::where('name', 'Cancelled')->first()->id;

        $overdueRequests = \App\Models\VisitRequest::where('status_id', $pendingStatusId)
            ->whereDate('from_date', '<', now())
            ->get();

        if ($overdueRequests->isEmpty()) {
            $this->info('Tidak ada request yang perlu dibatalkan.');
            return;
        }

        foreach ($overdueRequests as $request) {
            $request->update(['status_id' => $cancelledStatusId]);
            $this->info("Request ID: {$request->id} telah dibatalkan.");
        }
        $this->info('Proses pembatalan otomatis selesai.');
    }
}
