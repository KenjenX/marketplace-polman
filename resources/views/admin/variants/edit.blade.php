@extends('layouts.admin')

@section('content')
<div class="admin-card shadow-sm p-4 rounded-4 bg-white">
    <h2 class="mb-4 fw-bold" style="color: #013780;">Edit Variasi untuk: {{ $product->name }}</h2>

    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Nama Variasi</label>
            <input type="text" name="name" value="{{ old('name', $variant->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Detail Spesifikasi</label>
            <textarea name="specification" class="form-control @error('specification') is-invalid @enderror" rows="4">{{ old('specification', $variant->specification) }}</textarea>
            @error('specification') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $variant->price) }}" min="0" class="form-control @error('price') is-invalid @enderror">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $variant->stock) }}" min="0" class="form-control @error('stock') is-invalid @enderror">
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- INPUT GAMBAR EDIT --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Foto Variasi</label>
            
            {{-- Tampilkan foto lama jika ada --}}
            @if($variant->image)
                <div class="mb-2">
                    <p class="small text-muted mb-1">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Current Image" style="max-height: 100px; border-radius: 8px; border: 1px solid #eee;">
                </div>
            @endif

            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
            <div class="form-text small text-muted">Pilih file baru jika ingin mengganti foto varian ini.</div>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            
            {{-- Preview Gambar Baru --}}
            <div class="mt-2">
                <img id="imagePreview" src="#" alt="Preview" style="max-height: 150px; display: none; border-radius: 10px; border: 1px solid #ddd;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ old('status', $variant->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $variant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="d-flex gap-2 border-top pt-4">
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-secondary px-4 rounded-pill">Kembali</a>
            <button type="submit" class="btn btn-primary px-4 rounded-pill" style="background-color: #013780; border: none;">Update Variasi</button>
        </div>
    </form>
</div>

{{-- Script Preview Gambar --}}
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