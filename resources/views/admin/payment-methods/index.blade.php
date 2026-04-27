@extends('layouts.admin')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Metode Pembayaran</h2>
            <p class="text-muted mb-0">Kelola rekening dan instruksi pembayaran.</p>
        </div>
        <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">Tambah Metode</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Bank</th>
                    <th>No. Rekening</th>
                    <th>Atas Nama</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentMethods as $paymentMethod)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $paymentMethod->name }}</td>
                        <td>{{ $paymentMethod->type }}</td>
                        <td>{{ $paymentMethod->bank_name }}</td>
                        <td>{{ $paymentMethod->account_number }}</td>
                        <td>{{ $paymentMethod->account_name }}</td>
                        <td>
                            <span class="badge {{ $paymentMethod->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $paymentMethod->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.payment-methods.edit', $paymentMethod->id) }}" class="btn btn-outline-primary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.payment-methods.destroy', $paymentMethod->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus metode pembayaran ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada metode pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection