<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocialActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = SocialActivity::with('creator');

        if ($request->filled('search')) {
            $query->where('activity_name', 'like', "%{$request->search}%");
        }

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activities = $query->latest()->paginate(15);
        
        $stats = [
            'total' => SocialActivity::count(),
            'ongoing' => SocialActivity::where('status', 'ongoing')->count(),
            'completed' => SocialActivity::where('status', 'completed')->count(),
            'total_budget' => SocialActivity::sum('budget'),
            'total_beneficiaries' => SocialActivity::sum('beneficiary_count'),
        ];

        return view('admin.social-activities.index', compact('activities', 'stats'));
    }

    public function create()
    {
        return view('admin.social-activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_type' => 'required|in:bencana,keagamaan,sosial,lainnya',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'location' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'planned';
        $validated['beneficiary_count'] = 0;

        SocialActivity::create($validated);

        return redirect()->route('admin.social-activities.index')
            ->with('success', 'Kegiatan sosial berhasil dibuat!');
    }

    public function show(SocialActivity $socialActivity)
    {
        $socialActivity->load(['creator', 'beneficiaries']);
        return view('admin.social-activities.show', compact('socialActivity'));
    }

    public function edit(SocialActivity $socialActivity)
    {
        return view('admin.social-activities.edit', compact('socialActivity'));
    }

    public function update(Request $request, SocialActivity $socialActivity)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'activity_type' => 'required|in:bencana,keagamaan,sosial,lainnya',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'location' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        $socialActivity->update($validated);

        return redirect()->route('admin.social-activities.index')
            ->with('success', 'Kegiatan sosial berhasil diupdate!');
    }

    public function destroy(SocialActivity $socialActivity)
    {
        $socialActivity->delete();
        return redirect()->route('admin.social-activities.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

    public function beneficiaries(SocialActivity $socialActivity)
    {
        $beneficiaries = $socialActivity->beneficiaries()->paginate(20);
        return view('admin.social-activities.beneficiaries', compact('socialActivity', 'beneficiaries'));
    }
}