@extends('layouts.admin')

@section('content')
<div class="admin-card shadow-sm p-4 rounded-4 bg-white">
    <h2 class="mb-4 fw-bold" style="color: #013780;">Tambah Variasi untuk: {{ $product->name }}</h2>

    {{-- TAMBAHKAN enctype UNTUK UPLOAD GAMBAR --}}
    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Nama Variasi</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Warna Hitam / Ukuran L">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Detail Spesifikasi</label>
            <textarea name="specification" class="form-control @error('specification') is-invalid @enderror" rows="4" placeholder="Jelaskan detail khusus varian ini...">{{ old('specification') }}</textarea>
            @error('specification') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" class="form-control @error('price') is-invalid @enderror" placeholder="0">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Stok</label>
                <input type="number" name="stock" value="{{ old('stock') }}" min="0" class="form-control @error('stock') is-invalid @enderror" placeholder="0">
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- INPUT GAMBAR VARIASI BARU --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Foto Variasi <span class="text-muted small">(Opsional)</span></label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
            <div class="form-text">Jika kosong, akan menggunakan foto produk utama.</div>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            
            {{-- Preview Gambar --}}
            <div class="mt-2">
                <img id="imagePreview" src="#" alt="Preview" style="max-height: 150px; display: none; border-radius: 10px; border: 1px solid #ddd;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-secondary px-4 rounded-pill">Kembali</a>
            <button type="submit" class="btn btn-primary px-4 rounded-pill" style="background-color: #013780; border: none;">Simpan Variasi</button>
        </div>
    </form>
</div>

{{-- Script Preview Gambar biar Admin gak salah upload --}}
<script>
    imageInput.onchange = evt => {
        const [file] = imageInput.files
        if (file) {
            imagePreview.style.display = 'block';
            imagePreview.src = URL.createObjectURL(file)
        }
    }
</script>
@endsection