@extends('layouts.store')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h2 class="mb-1">Profile</h2>
                    <p class="text-muted mb-0">Kelola informasi akun dan alamat default checkout.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali ke Home</a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">Lihat Pesanan</a>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Tipe Akun</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->account_type === 'company' ? 'Perusahaan' : 'Individu' }}" readonly>
                </div>

                @if($user->account_type === 'company')
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama PIC / Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $user->contact_person) }}" class="form-control">
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Alamat Default Checkout</h4>

                <div class="mb-3">
                    <label class="form-label">Nama Penerima Default</label>
                    <input type="text" name="default_recipient_name" value="{{ old('default_recipient_name', $user->default_recipient_name) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Provinsi Default</label>
                    <input type="text" name="default_province" value="{{ old('default_province', $user->default_province) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kota / Kabupaten Default</label>
                    <input type="text" name="default_city" value="{{ old('default_city', $user->default_city) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kecamatan Default</label>
                    <input type="text" name="default_district" value="{{ old('default_district', $user->default_district) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Pos Default</label>
                    <input type="text" name="default_postal_code" value="{{ old('default_postal_code', $user->default_postal_code) }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Alamat Lengkap Default</label>
                    <textarea name="default_full_address" rows="4" class="form-control">{{ old('default_full_address', $user->default_full_address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
            </form>
        </div>

        <div class="content-card mb-4">
            <h3 class="mb-3">Ganti Password</h3>
            <p class="text-muted mb-4">Gunakan password yang panjang dan aman.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control">
                    @if($errors->updatePassword->get('current_password'))
                        <div class="text-danger small mt-1">
                            {{ $errors->updatePassword->first('current_password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                    @if($errors->updatePassword->get('password'))
                        <div class="text-danger small mt-1">
                            {{ $errors->updatePassword->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                    @if($errors->updatePassword->get('password_confirmation'))
                        <div class="text-danger small mt-1">
                            {{ $errors->updatePassword->first('password_confirmation') }}
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>

                @if (session('status') === 'password-updated')
                    <div class="text-success small mt-2">
                        Password berhasil diperbarui.
                    </div>
                @endif
            </form>
        </div>

        <div class="content-card border border-danger-subtle">
            <h3 class="mb-3 text-danger">Hapus Akun</h3>
            <p class="text-muted mb-4">
                Setelah akun dihapus, semua resource dan data akun akan ikut terhapus permanen.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="mb-3">
                    <label class="form-label">Masukkan Password untuk Konfirmasi</label>
                    <input type="password" name="password" class="form-control">
                    @if($errors->userDeletion->get('password'))
                        <div class="text-danger small mt-1">
                            {{ $errors->userDeletion->first('password') }}
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus akun ini?')">
                    Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>
@endsection