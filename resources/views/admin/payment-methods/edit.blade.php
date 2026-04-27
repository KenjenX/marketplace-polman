@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <h2 class="mb-4">Edit Metode Pembayaran</h2>

    <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Metode</label>
            <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe</label>
            <select name="type" class="form-select">
                <option value="bank_transfer" {{ old('type', $paymentMethod->type) === 'bank_transfer' ? 'selected' : '' }}>
                    Bank Transfer
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Rekening</label>
            <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Atas Nama</label>
            <input type="text" name="account_name" value="{{ old('account_name', $paymentMethod->account_name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Instruksi</label>
            <textarea name="instruction" class="form-control" rows="4">{{ old('instruction', $paymentMethod->instruction) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ old('is_active', $paymentMethod->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $paymentMethod->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection