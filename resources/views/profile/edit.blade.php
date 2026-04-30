@extends('layouts.store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="row g-4">
                {{-- Sidebar Profil --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-0 mb-4">
                        <div class="card-body text-center py-5">
                            <h4 class="fw-bold mb-1">{{ auth()->user()->name ?? auth()->user()->company_name }}</h4>
                            <span class="badge rounded-0 bg-primary px-3 py-2 text-uppercase mb-3" style="font-size: 10px; letter-spacing: 1px;">
                                {{ $user->account_type === 'company' ? 'Akun Perusahaan' : 'Akun Individu' }}
                            </span>
                            <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="list-group list-group-flush border-top">
                            <a href="#info-profil" class="list-group-item list-group-item-action border-0 py-3 active fw-bold" data-bs-toggle="list">
                                <i class="bi bi-person-gear me-2"></i> Informasi Akun
                            </a>
                            <a href="#alamat-default" class="list-group-item list-group-item-action border-0 py-3 fw-bold" data-bs-toggle="list">
                                <i class="bi bi-geo-alt me-2"></i> Alamat Default
                            </a>
                            <a href="#keamanan" class="list-group-item list-group-item-action border-0 py-3 fw-bold" data-bs-toggle="list">
                                <i class="bi bi-shield-lock me-2"></i> Keamanan
                            </a>
                            <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0 py-3 fw-bold text-primary">
                                <i class="bi bi-bag-check me-2"></i> Riwayat Pesanan
                            </a>
                        </div>
                    </div>

                    <div class="card border-danger border-opacity-25 shadow-sm rounded-0">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-danger mb-3">Zona Berbahaya</h6>
                            <p class="text-muted small mb-4">Setelah akun dihapus, semua resource dan data akun akan ikut terhapus permanen.</p>
                            <button class="btn btn-outline-danger w-100 rounded-0 fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Konten Utama Form --}}
                <div class="col-md-8">
                    <div class="tab-content">
                        {{-- Tab 1: Informasi Profil --}}
                        <div class="tab-pane fade show active" id="info-profil">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="fw-bold mb-0">Informasi Profil</h4>
                                    <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-0 px-3 border shadow-none">Kembali</a>
                                </div>
                                
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        @if($user->account_type === 'company')
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama Perusahaan</label>
                                                <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama PIC / Contact Person</label>
                                                <input type="text" name="contact_person" value="{{ old('contact_person', $user->contact_person) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                        @else
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bg-light border-0 py-2">
                                            </div>
                                        @endif

                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Nomor HP</label>
                                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control bg-light border-0 py-2">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                    </div>
                                    <div class="mt-5 text-end">
                                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tab 2: Alamat Default --}}
                        <div class="tab-pane fade" id="alamat-default">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <h4 class="fw-bold mb-4">Alamat Default Checkout</h4>
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Nama Penerima Default</label>
                                            <input type="text" name="default_recipient_name" value="{{ old('default_recipient_name', $user->default_recipient_name) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Provinsi</label>
                                            <input type="text" name="default_province" value="{{ old('default_province', $user->default_province) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kota / Kabupaten</label>
                                            <input type="text" name="default_city" value="{{ old('default_city', $user->default_city) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kecamatan</label>
                                            <input type="text" name="default_district" value="{{ old('default_district', $user->default_district) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kode Pos</label>
                                            <input type="text" name="default_postal_code" value="{{ old('default_postal_code', $user->default_postal_code) }}" class="form-control bg-light border-0 py-2">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                                            <textarea name="default_full_address" rows="3" class="form-control bg-light border-0 py-2">{{ old('default_full_address', $user->default_full_address) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="mt-5 text-end">
                                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">Update Alamat</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tab 3: Keamanan --}}
                        <div class="tab-pane fade" id="keamanan">
                            <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5">
                                <h4 class="fw-bold mb-2">Ganti Password</h4>
                                <p class="text-muted small mb-4">Gunakan password yang panjang dan aman.</p>

                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Password Saat Ini</label>
                                        <input type="password" name="current_password" class="form-control bg-light border-0 py-2 shadow-none">
                                        @if($errors->updatePassword->get('current_password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Password Baru</label>
                                        <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none">
                                        @if($errors->updatePassword->get('password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
                                        @endif
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-2 shadow-none">
                                    </div>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold rounded-0 shadow-sm">Update Password</button>
                                    @if (session('status') === 'password-updated')
                                        <div class="text-success small mt-3 fw-bold"><i class="bi bi-check-circle me-1"></i> Password berhasil diperbarui.</div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus Akun --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body py-4">
                    <p class="text-muted">Apakah Anda yakin ingin menghapus akun permanen? Masukkan password untuk mengonfirmasi tindakan ini.</p>
                    <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Masukkan Password Anda">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-0 border px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-0 px-4 fw-bold shadow-sm">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling khusus Profile POLMAN */
    .list-group-item.active {
        background-color: transparent !important;
        color: #013780 !important;
        border-left: 4px solid #013780 !important;
    }
    .form-control:focus {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
    }
    .btn, .card, .form-control, .modal-content, .list-group-item {
        border-radius: 0 !important;
    }
</style>
@endsection