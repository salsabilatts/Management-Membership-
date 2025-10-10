<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmoneyCard;
use App\Models\EmoneyTransaction;
use Illuminate\Http\Request;

class EmoneyController extends Controller
{
    public function index(Request $request)
    {
        $query = EmoneyCard::with('member');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhereHas('member', function($subQ) use ($search) {
                      $subQ->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cards = $query->latest()->paginate(20);
        
        $stats = [
            'total_cards' => EmoneyCard::active()->count(),
            'total_balance' => EmoneyCard::active()->sum('balance'),
            'daily_transactions' => EmoneyCard::whereDate('created_at', today())->count(),
        ];

        return view('admin.emoney.index', compact('cards', 'stats'));
    }

    public function show(EmoneyCard $emoneyCard)
    {
        $emoneyCard->load(['member', 'transactions' => function($query) {
            $query->latest()->limit(50);
        }]);

        return view('admin.emoney.show', compact('emoneyCard'));
    }

    public function toggleStatus(EmoneyCard $emoneyCard)
    {
        $newStatus = $emoneyCard->status === 'active' ? 'blocked' : 'active';
        $emoneyCard->update(['status' => $newStatus]);

        return back()->with('success', 'Status kartu berhasil diubah!');
    }
}