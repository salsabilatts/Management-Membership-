<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationAid;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationAidController extends Controller
{
    public function index(Request $request)
    {
        $query = EducationAid::with(['member', 'approvedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('school_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $educationAids = $query->latest()->paginate(20);
        
        $stats = [
            'total_pending' => EducationAid::pending()->count(),
            'total_approved' => EducationAid::where('status', 'approved')->count(),
            'total_disbursed' => EducationAid::disbursed()->count(),
            'total_amount' => EducationAid::where('status', 'disbursed')->sum('aid_amount'),
        ];

        return view('admin.education-aids.index', compact('educationAids', 'stats'));
    }

    public function show(EducationAid $educationAid)
    {
        $educationAid->load(['member', 'approvedBy']);
        return view('admin.education-aids.show', compact('educationAid'));
    }

    public function approve(Request $request, EducationAid $educationAid)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $educationAid->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'approved_by' => Auth::id(),
            'approval_date' => now(),
        ]);

        return redirect()->route('admin.education-aids.index')
            ->with('success', 'Pengajuan bantuan pendidikan berhasil diproses!');
    }

    public function disburse(EducationAid $educationAid)
    {
        if ($educationAid->status !== 'approved') {
            return back()->with('error', 'Hanya pengajuan yang disetujui yang dapat dicairkan!');
        }

        $educationAid->update([
            'status' => 'disbursed',
            'disbursement_date' => now(),
        ]);

        return back()->with('success', 'Bantuan berhasil dicairkan!');
    }
}