<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestVisit;
use App\Models\User;
use App\Models\VisitRequest; // Pastikan nama model ini sesuai dengan proyek Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Kartu Statistik ---
        $totalUsers = User::count();
        // Asumsi status 'pending' memiliki id = 1. Sesuaikan jika berbeda.
        $pendingRequests = VisitRequest::where('status_id', 1)->count();
        $guestsCheckedIn = GuestVisit::where('status', 'checked_in')->count();
        $guestsToday = GuestVisit::whereDate('time_in', today())->count();

        // --- Data untuk Grafik Request Bulanan ---
        $requestsPerMonth = VisitRequest::select(
                DB::raw('COUNT(id) as count'), 
                DB::raw('EXTRACT(MONTH FROM created_at) as month')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->all();
        
        $requestsChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $requestsChartData[] = $requestsPerMonth[$i] ?? 0;
        }

        // --- Data untuk Grafik Kunjungan Tamu Mingguan ---
        $guestsLast7Days = GuestVisit::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('DATE(created_at) as date')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date')
            ->all();

        $guestChartLabels = [];
        $guestChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $guestChartLabels[] = now()->subDays($i)->format('d M');
            $guestChartData[] = $guestsLast7Days[$date] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingRequests',
            'guestsCheckedIn',
            'guestsToday',
            'requestsChartData',
            'guestChartLabels',
            'guestChartData'
        ));
    }
}
