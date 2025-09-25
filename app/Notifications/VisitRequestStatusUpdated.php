<?php

namespace App\Notifications;

use App\Models\VisitRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        $status = $this->visitRequest->status->name;
        $destination = $this->visitRequest->destination;
        $url = route('requests.my');
        
        $greeting = 'Halo, ' . $notifiable->name . '.';
        $subject = '[VMS] Status Permintaan Anda: ' . $status;

        $line = match ($status) {
            'Approved'  => "Kabar baik! Permintaan kunjungan Anda ke **{$destination}** telah disetujui sepenuhnya.",
            'Rejected'  => "Mohon maaf, permintaan kunjungan Anda ke **{$destination}** telah ditolak.",
            'Cancelled' => "Permintaan kunjungan Anda ke **{$destination}** telah berhasil dibatalkan.",
            default     => "Status permintaan kunjungan Anda ke **{$destination}** telah diperbarui menjadi **{$status}**."
        };

        $mail = (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($line)
                    ->action('Lihat Detail Request', $url);

        // Tambahkan catatan dari approver jika ada
        if (!empty($this->visitRequest->approver_note)) {
            $mail->panel('Catatan dari Approver: ' . $this->visitRequest->approver_note);
        }
        
        $mail->line('Terima kasih.');

        return $mail;
    }
}