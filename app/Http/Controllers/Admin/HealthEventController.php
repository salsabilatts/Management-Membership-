<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthEventController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthEvent::with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('event_name', 'like', "%{$request->search}%");
        }

        $events = $query->latest()->paginate(15);
        
        $stats = [
            'total_events' => HealthEvent::count(),
            'open_events' => HealthEvent::open()->count(),
            'upcoming' => HealthEvent::upcoming()->count(),
        ];

        return view('admin.health-events.index', compact('events', 'stats'));
    }

    public function create()
    {
        return view('admin.health-events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'location' => 'required|string',
            'quota' => 'required|integer|min:1',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'open';
        $validated['registered_count'] = 0;

        HealthEvent::create($validated);

        return redirect()->route('admin.health-events.index')
            ->with('success', 'Event kesehatan berhasil dibuat!');
    }

    public function show(HealthEvent $healthEvent)
    {
        $healthEvent->load(['participants', 'creator']);
        return view('admin.health-events.show', compact('healthEvent'));
    }

    public function edit(HealthEvent $healthEvent)
    {
        return view('admin.health-events.edit', compact('healthEvent'));
    }

    public function update(Request $request, HealthEvent $healthEvent)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string',
            'quota' => 'required|integer|min:' . $healthEvent->registered_count,
            'status' => 'required|in:open,closed,completed,cancelled',
        ]);

        $healthEvent->update($validated);

        return redirect()->route('admin.health-events.index')
            ->with('success', 'Event kesehatan berhasil diupdate!');
    }

    public function destroy(HealthEvent $healthEvent)
    {
        if ($healthEvent->registered_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus event yang sudah memiliki peserta!');
        }

        $healthEvent->delete();
        return redirect()->route('admin.health-events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
