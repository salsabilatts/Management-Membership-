@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="page-header">
    <h1>Tambah User Baru</h1>
    <div class="breadcrumb">Home / Manajemen User / Tambah User</div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Registrasi Anggota</h3>
    </div>

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <!-- Nama Lengkap -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required 
                       placeholder="Masukkan nama lengkap" class="form-control @error('full_name') is-invalid @enderror">
                @error('full_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- NIK -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">NIK *</label>
                <input type="text" name="nik" value="{{ old('nik') }}" required 
                       placeholder="16 digit NIK" maxlength="16" class="form-control @error('nik') is-invalid @enderror">
                @error('nik')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       placeholder="email@example.com" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nomor Telepon *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required 
                       placeholder="08xxxxxxxxxx" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" 
                       class="form-control @error('birth_date') is-invalid @enderror">
                @error('birth_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Jenis Kelamin</label>
                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipe Membership -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tipe Membership *</label>
                <select name="membership_type_id" required class="form-control @error('membership_type_id') is-invalid @enderror">
                    <option value="">Pilih Tipe Membership</option>
                    @foreach($membershipTypes as $type)
                        <option value="{{ $type->id }}" {{ old('membership_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('membership_type_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Password *</label>
                <input type="password" name="password" required 
                       placeholder="Minimal 8 karakter" class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required 
                       placeholder="Ulangi password" class="form-control">
            </div>
        </div>

        <!-- Alamat -->
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Alamat Lengkap</label>
            <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap" 
                      class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
            @error('address')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
