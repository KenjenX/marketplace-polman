<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spesifikasi Produk</title>
</head>
<body>
    <h1>Spesifikasi Produk: {{ $product->name }}</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('admin.products.index') }}">Kembali ke Produk</a>
    <br><br>
    <a href="{{ route('admin.products.variants.create', $product->id) }}">Tambah Spesifikasi</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Spesifikasi</th>
                <th>Detail</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($variants as $variant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $variant->name }}</td>
                    <td>{{ $variant->specification }}</td>
                    <td>{{ $variant->price }}</td>
                    <td>{{ $variant->stock }}</td>
                    <td>{{ $variant->status }}</td>
                    <td>
                        <a href="{{ route('admin.variants.edit', $variant->id) }}">Edit</a>

                        <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus spesifikasi ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada spesifikasi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>