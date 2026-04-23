@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <h2 class="mb-4">Tambah Variasi untuk: {{ $product->name }}</h2>

    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Variasi</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Contoh: Ringan">
        </div>

        <div class="mb-3">
            <label class="form-label">Detail Spesifikasi</label>
            <textarea name="specification" class="form-control" rows="4" placeholder="Contoh: Kapasitas kecil, 220V">{{ old('specification') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" value="{{ old('price') }}" min="0" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stock" value="{{ old('stock') }}" min="0" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection