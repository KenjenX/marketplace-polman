<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
</head>
<body>
    <h1>{{ $product->name }}</h1>

    <p>Kategori: {{ $product->category->name }}</p>
    <p>Deskripsi: {{ $product->description }}</p>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h3>Daftar Spesifikasi / Variasi</h3>

    @forelse($product->variants as $variant)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <p><strong>Nama Variasi:</strong> {{ $variant->name }}</p>
            <p><strong>Spesifikasi:</strong> {{ $variant->specification }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($variant->price, 0, ',', '.') }}</p>
            <p><strong>Stok:</strong> {{ $variant->stock }}</p>

            @auth
                @if($variant->stock > 0)
                    <form action="{{ route('cart.add', $variant->id) }}" method="POST">
                        @csrf
                        <label>Jumlah</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $variant->stock }}">
                        <button type="submit">Tambah ke Keranjang</button>
                    </form>
                @else
                    <p><strong>Stok habis</strong></p>
                @endif
            @else
                <a href="{{ route('login') }}">Login untuk membeli</a>
            @endauth
        </div>
    @empty
        <p>Belum ada variasi untuk produk ini.</p>
    @endforelse

    <a href="{{ route('products.index') }}">Kembali ke daftar produk</a>
</body>
</html>