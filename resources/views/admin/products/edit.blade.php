<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
</head>
<body>
    <h1>Edit Produk</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Kategori</label><br>
            <select name="category_id">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>Nama Produk</label><br>
            <input type="text" name="name" value="{{ old('name', $product->name) }}">
        </div>

        <br>

        <div>
            <label>Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug', $product->slug) }}">
        </div>

        <br>

        <div>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <br>

        <div>
            <label>Status</label><br>
            <select name="status">
                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('admin.products.index') }}">Kembali</a>
</body>
</html>