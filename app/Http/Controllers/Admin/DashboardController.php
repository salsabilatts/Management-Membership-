<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Umkm;
use App\Models\EducationAid;
use App\Models\HealthEvent;
use App\Models\EmoneyCard;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => Member::active()->count(),
            'total_umkm' => Umkm::approved()->count(),
            'pending_submissions' => $this->getPendingSubmissions(),
            'total_emoney_balance' => EmoneyCard::active()->sum('balance'),
            'active_programs' => $this->getActivePrograms(),
            'daily_transactions' => $this->getDailyTransactions(),
            'recent_activities' => $this->getRecentActivities(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function getPendingSubmissions()
    {
        return Umkm::pending()->count() +
               EducationAid::pending()->count();
    }

    private function getActivePrograms()
    {
        return [
            'umkm' => Umkm::approved()->count(),
            'education' => EducationAid::where('status', '!=', 'rejected')->count(),
            'health' => HealthEvent::open()->count(),
        ];
    }

    private function getDailyTransactions()
    {
        return DB::table('emoney_transactions')
            ->whereDate('created_at', today())
            ->count();
    }

    private function getRecentActivities()
    {
        return DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(10)
            ->get();
    }
}