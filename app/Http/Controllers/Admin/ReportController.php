<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Member, Umkm, EducationAid, HealthEvent, LegalAid, SocialActivity, EmoneyCard, EmoneyTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        
        $data = [
            'date' => $date,
            'new_members' => Member::whereDate('created_at', $date)->count(),
            'umkm_submissions' => Umkm::whereDate('created_at', $date)->count(),
            'education_submissions' => EducationAid::whereDate('created_at', $date)->count(),
            'emoney_transactions' => EmoneyTransaction::whereDate('created_at', $date)->count(),
            'emoney_total' => EmoneyTransaction::whereDate('created_at', $date)->sum('amount'),
            'helpdesk_tickets' => DB::table('helpdesk_tickets')->whereDate('created_at', $date)->count(),
        ];

        return view('admin.reports.daily', compact('data'));
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;
        
        $data = [
            'month' => $month,
            'year' => $year,
            'total_members' => Member::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)->count(),
            'umkm_approved' => Umkm::where('verification_status', 'approved')
                ->whereMonth('verified_at', $month)
                ->whereYear('verified_at', $year)->count(),
            'education_disbursed' => EducationAid::where('status', 'disbursed')
                ->whereMonth('disbursement_date', $month)
                ->whereYear('disbursement_date', $year)->count(),
            'health_events' => HealthEvent::whereMonth('event_date', $month)
                ->whereYear('event_date', $year)->count(),
            'social_activities' => SocialActivity::whereMonth('activity_date', $month)
                ->whereYear('activity_date', $year)->count(),
            'emoney_transactions' => EmoneyTransaction::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)->count(),
            'emoney_total' => EmoneyTransaction::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)->sum('amount'),
        ];

        // Chart data for monthly transactions
        $transactionsByDay = EmoneyTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.monthly', compact('data', 'transactionsByDay'));
    }

    public function transactions(Request $request)
    {
        $query = EmoneyTransaction::with('emoneyCard.member');

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(50);
        
        $stats = [
            'total_transactions' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'by_type' => EmoneyTransaction::select('transaction_type', DB::raw('count(*) as count'))
                ->groupBy('transaction_type')
                ->pluck('count', 'transaction_type')
                ->toArray(),
        ];

        return view('admin.reports.transactions', compact('transactions', 'stats'));
    }

    public function export(Request $request)
    {
        $type = $request->type ?? 'members';
        $format = $request->format ?? 'excel'; // excel or pdf

        switch ($type) {
            case 'members':
                $data = Member::with('membershipType')->get();
                $view = 'admin.exports.members';
                $filename = 'members_' . now()->format('Y-m-d');
                break;
            
            case 'umkm':
                $data = Umkm::with('member')->get();
                $view = 'admin.exports.umkm';
                $filename = 'umkm_' . now()->format('Y-m-d');
                break;
            
            case 'education':
                $data = EducationAid::with('member')->get();
                $view = 'admin.exports.education';
                $filename = 'education_aids_' . now()->format('Y-m-d');
                break;
            
            case 'emoney':
                $data = EmoneyCard::with('member')->get();
                $view = 'admin.exports.emoney';
                $filename = 'emoney_cards_' . now()->format('Y-m-d');
                break;
            
            default:
                return back()->with('error', 'Tipe export tidak valid');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView($view, compact('data'));
            return $pdf->download($filename . '.pdf');
        }

        // Excel export akan ditambahkan dengan Excel Export class
        return back()->with('info', 'Export Excel sedang dalam pengembangan');
    }
}