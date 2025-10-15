@extends('layouts.admin')

@section('title', 'Data Pelaku UMKM')

@section('content')
<div class="page-header">
    <h1>Data Pelaku UMKM</h1>
    <div class="breadcrumb">Home / Modul UMKM / Data Pelaku Usaha</div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value">{{ number_format($stats['approved']) }}</div>
        <div class="stat-label">Usaha Terverifikasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
        <div class="stat-value">{{ number_format($stats['pending']) }}</div>
        <div class="stat-label">Menunggu Verifikasi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-value">Rp {{ number_format($stats['total_aid'], 0, ',', '.') }}</div>
        <div class="stat-label">Total Bantuan Modal</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pelaku UMKM</h3>
        <div style="display: flex; gap: 0.5rem;">
            <button class="btn btn-outline" onclick="toggleFilter()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.umkm.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div id="filterForm" style="display: none; padding: 1rem; background: var(--light); border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('admin.umkm.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="business_type" value="{{ request('business_type') }}" 
                           placeholder="Tipe Usaha" class="form-control">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('admin.umkm.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Search -->
    <div style="padding: 1rem; background: var(--light); border-bottom: 1px solid var(--border);">
        <form method="GET">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama usaha atau pemilik..." class="form-control">
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Usaha</th>
                    <th>Pemilik</th>
                    <th>Kategori</th>
                    <th>Bantuan Modal</th>
                    <th>Status</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($umkm as $u)
                <tr>
                    <td><strong>{{ $u->business_name }}</strong></td>
                    <td>{{ $u->member->full_name }}</td>
                    <td><span class="badge badge-primary">{{ $u->business_type }}</span></td>
                    <td>Rp {{ number_format($u->capital_aid, 0, ',', '.') }}</td>
                    <td>
                        @if($u->verification_status == 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($u->verification_status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.umkm.show', $u) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($u->verification_status == 'pending')
                        <button class="btn btn-success btn-sm" onclick="verifyModal({{ $u->id }})">
                            <i class="fas fa-check"></i> Verifikasi
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border);">
        <div>Menampilkan {{ $umkm->firstItem() ?? 0 }} - {{ $umkm->lastItem() ?? 0 }} dari {{ $umkm->total() }} data</div>
        <div>{{ $umkm->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
function toggleFilter() {
    const filter = document.getElementById('filterForm');
    filter.style.display = filter.style.display === 'none' ? 'block' : 'none';
}

function verifyModal(id) {
    // Implementasi modal verifikasi
    if(confirm('Verifikasi UMKM ini?')) {
        window.location.href = `/admin/umkm/${id}/verify`;
    }
}
</script>
@endpush
@endsection 