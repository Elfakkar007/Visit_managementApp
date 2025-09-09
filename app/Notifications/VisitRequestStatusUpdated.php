<?php

namespace App\Notifications;

use App\Models\VisitRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;

class VisitRequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visitRequest;

    public function __construct(VisitRequest $visitRequest)
    {
        $this->visitRequest = $visitRequest;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
         Log::info('--- Memulai pembuatan email untuk request ID: ' . $this->visitRequest->id);
        $status = $this->visitRequest->status->name;
        $destination = $this->visitRequest->destination;
        $url = route('requests.my');
        
        $mail = (new MailMessage)
                    ->subject("Status Permintaan Kunjungan Anda: {$status}")
                    ->greeting('Halo, ' . $notifiable->name)
                    ->line("Status permintaan kunjungan Anda ke **{$destination}** telah diperbarui menjadi **{$status}**.")
                    ->action('Lihat Request Saya', $url);

        // Tambahkan alasan penolakan jika ada
        if ($status === 'Rejected' && !empty($this->visitRequest->rejection_reason)) {
            $mail->line('Alasan Penolakan: ' . $this->visitRequest->rejection_reason);
        }
        
        $mail->line('Terima kasih.');

        Log::info('--- Email untuk request ID: ' . $this->visitRequest->id . ' berhasil dibuat dan siap dikirim.');


        return $mail;
    }
}