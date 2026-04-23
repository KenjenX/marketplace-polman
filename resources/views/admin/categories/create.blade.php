<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
</head>
<body>
    <h1>Tambah Kategori</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div>
            <label>Nama Kategori</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <br>

        <div>
            <label>Slug</label><br>
            <input type="text" name="slug" value="{{ old('slug') }}">
        </div>

        <br>

        <div>
            <label>Deskripsi</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('admin.categories.index') }}">Kembali</a>
</body>
</html>