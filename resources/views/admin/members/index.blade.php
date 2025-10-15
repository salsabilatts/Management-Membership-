@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
<div class="page-header">
    <h1>Data Anggota</h1>
    <div class="breadcrumb">Home / Manajemen User / Data Anggota</div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Anggota Terdaftar</h3>
        <div style="display: flex; gap: 0.5rem;">
            <button class="btn btn-outline" onclick="toggleFilter()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.members.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div id="filterForm" style="display: none; padding: 1rem; background: var(--light); border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('admin.members.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div>
                    <label>Membership</label>
                    <select name="membership_type" class="form-control">
                        <option value="">Semua Tipe</option>
                        @foreach($membershipTypes as $type)
                            <option value="{{ $type->id }}" {{ request('membership_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Bar -->
    <div style="padding: 1rem; background: var(--light); border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('admin.members.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama, email, NIK, atau telepon..." 
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px;">
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Membership</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>#{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $member->full_name }}</strong></td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone }}</td>
                    <td><span class="badge badge-primary">{{ $member->membershipType->name }}</span></td>
                    <td>
                        @if($member->status == 'active')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($member->status == 'inactive')
                            <span class="badge badge-danger">Nonaktif</span>
                        @else
                            <span class="badge badge-warning">Suspended</span>
                        @endif
                    </td>
                    <td>{{ $member->join_date->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Yakin hapus member ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border);">
        <div style="color: var(--secondary);">
            Menampilkan {{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }} dari {{ $members->total() }} data
        </div>
        <div>
            {{ $members->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFilter() {
    const filter = document.getElementById('filterForm');
    filter.style.display = filter.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
@endsection