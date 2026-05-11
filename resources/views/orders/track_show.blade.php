@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Lacak Pesanan: #{{ $order->order_code }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nomor Resi</p>
                    <h6>{{ $data['summary']['waybill_number'] }} ({{ $data['summary']['courier_name'] }})</h6>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-1">Status Terakhir</p>
                    <span class="badge bg-primary">{{ $data['summary']['status'] }}</span>
                </div>
            </div>

            <hr>

            {{-- Struktur Timeline --}}
            <div class="tracking-timeline mt-4">
                @foreach(array_reverse($data['manifest']) as $history)
                <div class="d-flex mb-4">
                    <div class="me-3 text-center" style="width: 80px;">
                        <small class="d-block fw-bold">{{ $history['manifest_date'] }}</small>
                        <small class="text-muted">{{ $history['manifest_time'] }}</small>
                    </div>
                    <div class="flex-grow-1 border-start ps-4 position-relative">
                        {{-- Dot Timeline --}}
                        <div class="position-absolute start-0 translate-middle-x bg-primary rounded-circle" 
                             style="width: 12px; height: 12px; margin-top: 5px;"></div>
                        
                        <h6 class="mb-1">{{ $history['manifest_description'] }}</h6>
                        <p class="small text-muted">{{ $history['city_name'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-timeline .border-start {
        border-width: 2px !important;
        border-color: #e9ecef !important;
    }
</style>
@endsection