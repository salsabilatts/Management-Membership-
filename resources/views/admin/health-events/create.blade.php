@extends('layouts.admin')

@section('title', 'Buat Event Kesehatan')

@section('content')
<div class="page-header">
    <h1>Buat Event Kesehatan</h1>
    <div class="breadcrumb">Home / Modul Kesehatan / Buat Event</div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Event Layanan Kesehatan</h3>
    </div>

    <form action="{{ route('admin.health-events.store') }}" method="POST" style="padding: 1.5rem;">
        @csrf

        <div style="display: grid; gap: 1.5rem;">
            <!-- Nama Event -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Event *</label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" required 
                       placeholder="Contoh: Medical Check-Up Gratis" 
                       class="form-control @error('event_name') is-invalid @enderror">
                @error('event_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal dan Waktu -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tanggal Event *</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}" required 
                           min="{{ date('Y-m-d') }}" class="form-control @error('event_date') is-invalid @enderror">
                    @error('event_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Waktu *</label>
                    <input type="time" name="event_time" value="{{ old('event_time') }}" required 
                           class="form-control @error('event_time') is-invalid @enderror">
                    @error('event_time')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Lokasi -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Lokasi Event *</label>
                <input type="text" name="location" value="{{ old('location') }}" required 
                       placeholder="Alamat lengkap lokasi" 
                       class="form-control @error('location') is-invalid @enderror">
                @error('location')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Kuota -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kuota Peserta *</label>
                <input type="number" name="quota" value="{{ old('quota') }}" required min="1" 
                       placeholder="Jumlah maksimal peserta" 
                       class="form-control @error('quota') is-invalid @enderror">
                @error('quota')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Deskripsi Event</label>
                <textarea name="description" rows="4" 
                          placeholder="Jelaskan detail event dan layanan yang tersedia" 
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <a href="{{ route('admin.health-events.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Buat Event
            </button>
        </div>
    </form>
</div>
@endsection