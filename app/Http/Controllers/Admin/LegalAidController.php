<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalAid;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegalAidController extends Controller
{
    public function index(Request $request)
    {
        $query = LegalAid::with(['member', 'institution', 'verifiedBy']);

        if ($request->filled('search')) {
            $query->where('case_number', 'like', "%{$request->search}%")
                  ->orWhere('case_title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('case_type')) {
            $query->where('case_type', $request->case_type);
        }

        $legalAids = $query->latest()->paginate(20);
        
        $stats = [
            'pending' => LegalAid::where('status', 'pending')->count(),
            'in_process' => LegalAid::where('status', 'in_process')->count(),
            'completed' => LegalAid::where('status', 'completed')->count(),
        ];

        return view('admin.legal-aids.index', compact('legalAids', 'stats'));
    }

    public function show(LegalAid $legalAid)
    {
        $legalAid->load(['member', 'institution', 'verifiedBy']);
        return view('admin.legal-aids.show', compact('legalAid'));
    }

    public function verify(Request $request, LegalAid $legalAid)
    {
        $validated = $request->validate([
            'status' => 'required|in:in_process,completed,rejected',
            'institution_id' => 'nullable|exists:institutions,id',
            'notes' => 'nullable|string',
        ]);

        $legalAid->update([
            'status' => $validated['status'],
            'institution_id' => $validated['institution_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'verified_by' => Auth::id(),
            'verification_date' => now(),
        ]);

        return redirect()->route('admin.legal-aids.index')
            ->with('success', 'Status bantuan hukum berhasil diupdate!');
    }
}