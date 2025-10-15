@extends('layouts.admin')

@section('title', 'E-Money Management')

@section('content')
<div class="page-header">
    <h1>E-Money Management</h1>
    <div class="breadcrumb">Home / E-Money Management</div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-credit-card"></i></div>
        <div class="stat-value">{{ number_format($stats['total_cards']) }}</div>
        <div class="stat-label">Total Kartu Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-wallet"></i></div>
        <div class="stat-value">Rp {{ number_format($stats['total_balance'], 0, ',', '.') }}</div>
        <div class="stat-label">Total Saldo Beredar</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fas fa-exchange-alt"></i></div>
        <div class="stat-value">{{ number_format($stats['daily_transactions']) }}</div>
        <div class="stat-label">Transaksi Hari Ini</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kartu E-Money Anggota</h3>
        <a href="{{ route('admin.reports.export', ['type' => 'emoney']) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Laporan
        </a>
    </div>

    <!-- Search -->
    <div style="padding: 1rem; background: var(--light); border-bottom: 1px solid var(--border);">
        <form method="GET">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama pemegang atau nomor kartu..." class="form-control">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Diblokir</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Nama Pemegang</th>
                    <th>Nomor Kartu</th>
                    <th>Saldo</th>
                    <th>Status Kartu</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                <tr>
                    <td>#{{ str_pad($card->member->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $card->member->full_name }}</strong></td>
                    <td>{{ $card->masked_card_number }}</td>
                    <td><strong>Rp {{ number_format($card->balance, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($card->status == 'active')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($card->status == 'blocked')
                            <span class="badge badge-danger">Diblokir</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $card->issued_date->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.emoney.show', $card) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-history"></i> Riwayat
                        </a>
                        <form action="{{ route('admin.emoney.toggle-status', $card) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" 
                                    onclick="return confirm('Ubah status kartu ini?')">
                                <i class="fas fa-ban"></i> 
                                {{ $card->status == 'active' ? 'Blokir' : 'Aktifkan' }}
                            </button>
                        </form>
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
        <div>Menampilkan {{ $cards->firstItem() ?? 0 }} - {{ $cards->lastItem() ?? 0 }} dari {{ $cards->total() }} data</div>
        <div>{{ $cards->links() }}</div>
    </div>
</div>
@endsection
