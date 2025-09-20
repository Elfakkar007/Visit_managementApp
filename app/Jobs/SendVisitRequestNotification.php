<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendVisitRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipients;
    protected $notification;

    public function __construct($recipients, $notification)
    {
        $this->recipients = $recipients;
        $this->notification = $notification;
    }

    public function handle(): void
    {
        Notification::send($this->recipients, $this->notification);
    }
}
