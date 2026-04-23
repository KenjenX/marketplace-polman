<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Daftar Produk</h1>

    <form method="GET" action="{{ route('products.index') }}">
        <div>
            <label>Cari Produk</label><br>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk...">
        </div>

        <br>

        <div>
            <label>Kategori</label><br>
            <select name="category">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <button type="submit">Cari / Filter</button>

        <a href="{{ route('products.index') }}">Reset</a>
    </form>

    <br>

    @forelse($products as $product)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <h3>{{ $product->name }}</h3>
            <p>Kategori: {{ $product->category->name }}</p>
            <p>{{ $product->description }}</p>
            <p>
                Harga mulai dari:
                @if($product->variants->count() > 0)
                    Rp {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                @else
                    -
                @endif
            </p>
            <a href="{{ route('products.show', $product->slug) }}">Lihat Detail</a>
        </div>
    @empty
        <p>Produk tidak ditemukan</p>
    @endforelse
</body>
</html>