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
        $url = route('requests.approval');

        return (new MailMessage)
            ->subject('[VMS] Permintaan Persetujuan Baru: ' . $requesterName)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Anda memiliki satu permintaan kunjungan dinas baru yang membutuhkan tinjauan Anda.')
            ->line('**Pemohon:** ' . $requesterName)
            ->line('**Tujuan:** ' . $destination)
            ->action('Lihat & Proses Permintaan', $url)
            ->line('Mohon untuk segera ditinjau. Terima kasih.');
    }
}