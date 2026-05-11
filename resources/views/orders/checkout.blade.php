<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 mb-5">
    <h3>Selesaikan Pesanan Anda</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        {{-- ALAMAT PENGIRIMAN --}}
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Informasi Pengiriman</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="province" class="form-control" placeholder="Jawa Barat" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kota / Kabupaten</label>
                        <select name="city_id" class="form-select" required>
                            <option value="">-- Pilih Kota Tujuan --</option>
                            <option value="54">Kota Bogor</option>
                            <option value="55">Kabupaten Bogor</option>
                            <option value="23">Kota Bandung</option>
                            <option value="153">Kabupaten Bandung</option>
                            </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="district" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap (Jalan, No Rumah, RT/RW)</label>
                    <textarea name="full_address" class="form-control" rows="3" required>{{ old('full_address') }}</textarea>
                </div>
            </div>
        </div>

        {{-- METODE PENGIRIMAN --}}
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Opsi Pengiriman</div>
            <div class="card-body">
                <select name="shipping_method" class="form-select" required>
                    <option value="">-- Pilih Kurir --</option>
                    <option value="jne">JNE (Reguler/YES)</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="tiki">TIKI</option>
                </select>
                <small class="text-muted">*Biaya pengiriman dihitung otomatis berdasarkan berat total barang.</small>
            </div>
        </div>

        {{-- METODE PEMBAYARAN --}}
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Metode Pembayaran</div>
            <div class="card-body">
                <select name="payment_method_id" class="form-select" required>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- RINGKASAN PESANAN --}}
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Ringkasan Pesanan</div>
            <div class="card-body">
                <ul class="list-group mb-3">
                    @foreach($cart->items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $item->variant->product->name }} - {{ $item->variant->name }} (x{{ $item->quantity }})
                            <span>Rp {{ number_format($item->variant->price * $item->quantity) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex justify-content-between">
                    <strong>Total Barang:</strong>
                    <strong>Rp {{ number_format($cart->items->sum(fn($i) => $i->variant->price * $i->quantity)) }}</strong>
                </div>
                <p class="text-info small">*Ongkir akan ditambahkan pada total akhir setelah tombol klik diproses.</p>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Proses Pembayaran</button>
    </form>
</div>

</body>
</html>