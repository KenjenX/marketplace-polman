<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Daftar Produk</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('admin.products.create') }}">Tambah Produk</a>
    <br><br>
    <a href="{{ route('admin.categories.index') }}">Lihat Kategori</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Slug</th>
                <th>Jumlah Variasi</th>
                <th>Harga Mulai Dari</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->slug }}</td>
                    <td>{{ $product->variants->count() }}</td>
                    <td>
                        @if($product->variants->count() > 0)
                            Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $product->status }}</td>
                    <td>
                        <a href="{{ route('admin.products.variants.index', $product->id) }}">Kelola Spesifikasi</a>
                        <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>

                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>