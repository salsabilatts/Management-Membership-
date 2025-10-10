<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['user', 'membershipType']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by membership type
        if ($request->filled('membership_type')) {
            $query->where('membership_type_id', $request->membership_type);
        }

        $members = $query->latest()->paginate(20);

        return view('admin.members.index', compact('members', 'membershipTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:members,nik|digits:16',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'membership_type_id' => 'required|exists:membership_types,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign role
            $user->assignRole('member');

            // Create member profile
            $member = Member::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'membership_type_id' => $validated['membership_type_id'],
                'status' => 'active',
                'join_date' => now(),
            ]);

            DB::commit();
            return redirect()->route('admin.members.index')->with('success', 'Member berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan member: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:members,nik,' . $member->id,
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'membership_type_id' => 'required|exists:membership_types,id',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.index')->with('success', 'Data member berhasil diupdate!');
    }

    public function destroy(Member $member)
    {
        try {
            $member->delete();
            return redirect()->route('admin.members.index')->with('success', 'Member berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus member: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Member $member)
    {
        $newStatus = $member->status === 'active' ? 'inactive' : 'active';
        $member->update(['status' => $newStatus]);

        return back()->with('success', 'Status member berhasil diubah!');
    }
}