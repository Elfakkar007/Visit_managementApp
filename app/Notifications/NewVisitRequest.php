<?php

namespace App\Notifications;

use App\Models\VisitRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVisitRequest extends Notification implements ShouldQueue
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
        $requesterName = $this->visitRequest->user->name;
        $destination = $this->visitRequest->destination;
        // Arahkan tombol ke halaman approval
        $url = route('requests.approval');

        return (new MailMessage)
                    ->subject('Permintaan Kunjungan Baru dari ' . $requesterName)
                    ->greeting('Halo,')
                    ->line("Anda memiliki permintaan kunjungan baru yang perlu ditinjau dari **{$requesterName}**.")
                    ->line("Tujuan Kunjungan: **{$destination}**.")
                    ->action('Lihat & Proses Request', $url)
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }
}