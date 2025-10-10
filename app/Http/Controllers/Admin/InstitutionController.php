<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $institutions = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Institution::count(),
            'active' => Institution::where('status', 'active')->count(),
            'by_type' => Institution::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->pluck('total', 'type')
                ->toArray(),
        ];

        return view('admin.institutions.index', compact('institutions', 'stats'));
    }

    public function create()
    {
        return view('admin.institutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sekolah,dinas_kesehatan,lbh,lembaga_sosial,lainnya',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string|max:255',
            'programs' => 'nullable|array',
        ]);

        $validated['programs'] = json_encode($validated['programs'] ?? []);
        $validated['status'] = 'active';

        Institution::create($validated);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Lembaga berhasil ditambahkan!');
    }

    public function show(Institution $institution)
    {
        return view('admin.institutions.show', compact('institution'));
    }

    public function edit(Institution $institution)
    {
        return view('admin.institutions.edit', compact('institution'));
    }

    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sekolah,dinas_kesehatan,lbh,lembaga_sosial,lainnya',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'programs' => 'nullable|array',
        ]);

        $validated['programs'] = json_encode($validated['programs'] ?? []);

        $institution->update($validated);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Lembaga berhasil diupdate!');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();
        return redirect()->route('admin.institutions.index')
            ->with('success', 'Lembaga berhasil dihapus!');
    }
}