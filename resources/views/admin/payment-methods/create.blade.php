@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <h2 class="mb-4">Tambah Metode Pembayaran</h2>

    <form action="{{ route('admin.payment-methods.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Metode</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Transfer Bank BCA">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe</label>
            <select name="type" class="form-select">
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Rekening</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Atas Nama</label>
            <input type="text" name="account_name" value="{{ old('account_name') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Instruksi</label>
            <textarea name="instruction" class="form-control" rows="4">{{ old('instruction') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection