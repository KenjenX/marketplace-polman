<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Spesifikasi</title>
</head>
<body>
    <h1>Edit Spesifikasi untuk: {{ $product->name }}</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Nama Spesifikasi</label><br>
            <input type="text" name="name" value="{{ old('name', $variant->name) }}">
        </div>

        <br>

        <div>
            <label>Detail Spesifikasi</label><br>
            <textarea name="specification">{{ old('specification', $variant->specification) }}</textarea>
        </div>

        <br>

        <div>
            <label>Harga</label><br>
            <input type="number" name="price" value="{{ old('price', $variant->price) }}" min="0">
        </div>

        <br>

        <div>
            <label>Stok</label><br>
            <input type="number" name="stock" value="{{ old('stock', $variant->stock) }}" min="0">
        </div>

        <br>

        <div>
            <label>Status</label><br>
            <select name="status">
                <option value="active" {{ old('status', $variant->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $variant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('admin.products.variants.index', $product->id) }}">Kembali</a>
</body>
</html>