@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <div class="breadcrumb">Home / Dashboard</div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_members']) }}</div>
        <div class="stat-label">Total Anggota Aktif</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon success">
                <i class="fas fa-store"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['total_umkm']) }}</div>
        <div class="stat-label">Pelaku UMKM</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ number_format($stats['pending_submissions']) }}</div>
        <div class="stat-label">Pengajuan Menunggu</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon danger">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
        <div class="stat-value">Rp {{ number_format($stats['total_emoney_balance'], 0, ',', '.') }}</div>
        <div class="stat-label">Total Saldo E-Money</div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Aktivitas Terbaru</h3>
        <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline">
            <i class="fas fa-history"></i> Lihat Semua
        </a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aktivitas</th>
                    <th>Model</th>
                </tr>
            </thead>
            <tbody>
             @forelse($stats['recent_activities'] ?? [] as $activity)
                <tr>
                    <td>{{ $activity->created_at->format('H:i') }}</td>
                    <td>{{ $activity->user_name }}</td>
                    <td>{{ $activity->action }}</td>
                    <td><span class="badge badge-primary">{{ $activity->model ?? '-' }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada aktivitas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Program Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Program Aktif</h3>
        </div>
        <div style="padding: 1rem 0;">
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                <span>UMKM - Bantuan Modal</span>
                <strong>{{ $stats['active_programs']['umkm'] }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                <span>Pendidikan - PIP/KIP/BIB</span>
                <strong>{{ $stats['active_programs']['education'] }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                <span>Kesehatan - Event Aktif</span>
                <strong>{{ $stats['active_programs']['health'] }}</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transaksi Hari Ini</h3>
        </div>
        <div style="padding: 2rem; text-align: center;">
            <div style="font-size: 3rem; font-weight: 700; color: var(--primary);">
                {{ number_format($stats['daily_transactions']) }}
            </div>
            <div style="color: var(--secondary); margin-top: 0.5rem;">Total Transaksi E-Money</div>
        </div>
    </div>
</div>
@endsection