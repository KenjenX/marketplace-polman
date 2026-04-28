@extends('layouts.store')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="text-center mb-4">
                <h2 class="mb-1">Register</h2>
                <p class="text-muted mb-0">Buat akun individu atau perusahaan</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tipe Akun</label>
                    <select id="account_type" name="account_type" class="form-select">
                        <option value="individual" {{ old('account_type') === 'individual' ? 'selected' : '' }}>Individu</option>
                        <option value="company" {{ old('account_type') === 'company' ? 'selected' : '' }}>Perusahaan</option>
                    </select>
                </div>

                <div class="mb-3" id="individual_fields">
                    <label class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>

                <div id="company_fields" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama PIC / Contact Person</label>
                        <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali ke Home</a>
                        <a href="{{ route('login') }}" class="btn btn-link px-0">Sudah punya akun?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountType = document.getElementById('account_type');
        const individualFields = document.getElementById('individual_fields');
        const companyFields = document.getElementById('company_fields');

        function toggleFields() {
            if (accountType.value === 'company') {
                individualFields.classList.add('d-none');
                companyFields.classList.remove('d-none');
            } else {
                individualFields.classList.remove('d-none');
                companyFields.classList.add('d-none');
            }
        }

        accountType.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
@endsection