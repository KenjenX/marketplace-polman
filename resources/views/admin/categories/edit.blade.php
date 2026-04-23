<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
</head>
<body>
    <h1>Edit Kategori</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Nama Kategori</label><br>
            <input type="text" name="name" value="{{ old('name', $category->name) }}">
        </div>

        <br>

        <div>
            <label>Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}">
        </div>

        <br>

        <div>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description', $category->description) }}</textarea>
        </div>

        <br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('admin.categories.index') }}">Kembali</a>
</body>
</html>