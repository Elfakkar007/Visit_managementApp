<?php

namespace App\Livewire\Receptionist;

use Livewire\Component;
use App\Models\GuestVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class OverdueGuestsNotifier extends Component
{
    public $overdueGuests = [];
    public $overdueGuestsCount = 0;

    public function fetchOverdueGuests()
    {
        $twentyFourHoursAgo = Carbon::now()->subHours(24);

        $guests = GuestVisit::with('guest')
            ->where('status', 'checked_in')
            ->whereRaw('NOW() >= time_in + (notification_duration_hours * INTERVAL \'1 hour\')')
            ->where('time_in', '>', $twentyFourHoursAgo)
            ->latest('time_in')
            ->get();
            
        $this->overdueGuests = $guests;
        $this->overdueGuestsCount = $guests->count();
    }

    public function render()
    {
        $this->fetchOverdueGuests();
        return view('livewire.receptionist.overdue-guests-notifier');
    }
}