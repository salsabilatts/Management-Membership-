<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with(['member', 'verifiedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhereHas('member', function($subQ) use ($search) {
                      $subQ->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        if ($request->filled('business_type')) {
            $query->where('business_type', $request->business_type);
        }

        $umkm = $query->latest()->paginate(20);
        $stats = [
            'total' => Umkm::count(),
            'approved' => Umkm::approved()->count(),
            'pending' => Umkm::pending()->count(),
            'total_aid' => Umkm::sum('capital_aid'),
        ];

        return view('admin.umkm.index', compact('umkm', 'stats'));
    }

    public function show(Umkm $umkm)
    {
        $umkm->load(['member', 'verifiedBy']);
        return view('admin.umkm.show', compact('umkm'));
    }

    public function verify(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:approved,rejected',
            'verification_notes' => 'nullable|string',
            'capital_aid' => 'nullable|numeric|min:0',
        ]);

        $umkm->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'capital_aid' => $validated['capital_aid'] ?? 0,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'aid_date' => $validated['verification_status'] === 'approved' ? now() : null,
        ]);

        $message = $validated['verification_status'] === 'approved' 
            ? 'UMKM berhasil disetujui!' 
            : 'UMKM ditolak!';

        return redirect()->route('admin.umkm.index')->with('success', $message);
    }

    public function exportReport(Request $request)
    {
        // Export logic akan ditambahkan dengan Laravel Excel
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
}
