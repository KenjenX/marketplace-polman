<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Spesifikasi</title>
</head>
<body>
    <h1>Tambah Spesifikasi untuk: {{ $product->name }}</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
        @csrf

        <div>
            <label>Nama Spesifikasi</label><br>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Ringan">
        </div>

        <br>

        <div>
            <label>Detail Spesifikasi</label><br>
            <textarea name="specification" placeholder="Contoh: Kapasitas kecil, 220V">{{ old('specification') }}</textarea>
        </div>

        <br>

        <div>
            <label>Harga</label><br>
            <input type="number" name="price" value="{{ old('price') }}" min="0">
        </div>

        <br>

        <div>
            <label>Stok</label><br>
            <input type="number" name="stock" value="{{ old('stock') }}" min="0">
        </div>

        <br>

        <div>
            <label>Status</label><br>
            <select name="status">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('admin.products.variants.index', $product->id) }}">Kembali</a>
</body>
</html>