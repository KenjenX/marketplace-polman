@extends('layouts.store')

@section('content')
<div class="content-card">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="fw-bold mb-0 text-dark">Lacak Pengiriman</h4>
        <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-neumorph-btn btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Stepper Progress Neumorphism --}}
    <div class="stepper-wrapper mb-5">
        <div class="stepper-container">
            {{-- Line Background --}}
            <div class="stepper-line">
                @php
                    $progressWidth = '0%';
                    if($order->status == 'processing') $progressWidth = '33%';
                    if($order->status == 'shipped') $progressWidth = '66%';
                    if($order->status == 'completed') $progressWidth = '100%';

                    $steps = [
                        ['status' => 'pending', 'label' => 'Pesanan dibuat', 'icon' => 'bi-file-earmark-text'],
                        ['status' => 'processing', 'label' => 'Menunggu kurir', 'icon' => 'bi-box-seam'],
                        ['status' => 'shipped', 'label' => 'Sedang transit', 'icon' => 'bi-truck'],
                        ['status' => 'completed', 'label' => 'Pesanan diantarkan', 'icon' => 'bi-check2-circle']
                    ];
                    
                    function isStepActive($currentStatus, $stepStatus) {
                        $orderMap = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'completed' => 3];
                        return $orderMap[$currentStatus] >= $orderMap[$stepStatus];
                    }
                @endphp
                <div class="stepper-line-fill" style="width: {{ $progressWidth }}"></div>
            </div>

            @foreach($steps as $step)
                <div class="step-item">
                    <div class="step-icon-container {{ isStepActive($order->status, $step['status']) ? 'active' : '' }} {{ $order->status == $step['status'] ? 'pulse-blue' : '' }}">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </div>
                    <div class="step-label {{ isStepActive($order->status, $step['status']) ? 'active' : '' }}">
                        {{ $step['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- Panel Informasi (Kiri) --}}
            <div class="col-md-4 border-end">
                <div class="info-box-neumorph mb-4">
                    <label class="text-muted small d-block">Nomor Resi</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-primary mb-0" id="trackingNumber">{{ $order->tracking_number ?? 'Belum ada resi' }}</h5>
                        @if($order->tracking_number)
                            <button class="btn btn-link p-0 text-secondary" onclick="copyToClipboard()" title="Salin Resi">
                                <i class="bi bi-clipboard" id="copyIcon"></i>
                            </button>
                        @endif
                    </div>
                </div>
                
                <div class="info-box-neumorph mb-4">
                    <label class="text-muted small d-block">Kurir</label>
                    <h5 class="fw-bold text-uppercase mb-0 text-dark">{{ $order->courier_code ?? 'JNE' }}</h5>
                </div>

                <div class="info-box-neumorph mb-4">
                    <label class="text-muted small d-block">Status Terakhir</label>
                    <span class="badge badge-neumorph">
                        {{ $trackingData['summary']['status'] ?? ($order->status == 'shipped' ? 'Dalam Perjalanan' : $order->status) }}
                    </span>
                </div>
            </div>

            {{-- Panel Timeline (Kanan) --}}
            <div class="col-md-8">
                @if(isset($trackingData['history']) && count($trackingData['history']) > 0)
                    <div class="tracking-list ms-lg-4">
                        @foreach($trackingData['history'] as $index => $item)
                            <div class="d-flex mb-4">
                                <div class="me-3 text-center" style="min-width: 100px;">
                                    <small class="text-dark fw-bold d-block">{{ explode(' ', $item['date'])[0] }}</small>
                                    <small class="text-muted">{{ explode(' ', $item['date'])[1] }}</small>
                                </div>
                                <div class="flex-grow-1 border-start ps-4 position-relative pb-2">
                                    <div class="position-absolute shadow-sm timeline-dot {{ $index === 0 ? 'dot-active' : '' }}"
                                         style="left: -11px; top: 0; width: 22px; height: 22px;
                                                background: {{ $index === 0 ? '#0d6efd' : '#e9ecef' }};
                                                border-radius: 50%; border: 4px solid #fff;
                                                z-index: 2;">
                                    </div>
                                    <h6 class="fw-bold mb-1 {{ $index === 0 ? 'text-primary' : 'text-dark' }}">
                                        {{ $item['desc'] }}
                                    </h6>
                                    @if($index === 0)
                                        <span class="badge bg-light text-primary border border-primary-subtle small">Terbaru</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-box-seam text-secondary-subtle" style="font-size: 5rem;"></i>
                        <h5 class="text-secondary mt-3">Data pelacakan belum tersedia</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --neu-bg: #ffffff;
        --neu-light: #ffffff;
        --neu-shadow: #d1d9e6;
        --neu-primary: #0d6efd;
    }

    .content-card {
        background: var(--neu-bg);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 9px 9px 16px var(--neu-shadow), -9px -9px 16px var(--neu-light);
        border: 1px solid rgba(255,255,255,0.4);
    }

    .stepper-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        padding: 0 10px;
    }

    .stepper-line {
        position: absolute;
        top: 25px;
        left: 5%;
        right: 5%;
        height: 6px;
        background: #f0f2f5;
        z-index: 0;
        box-shadow: inset 2px 2px 5px var(--neu-shadow);
        border-radius: 10px;
    }

    .stepper-line-fill {
        height: 100%;
        background: var(--neu-primary);
        border-radius: 10px;
        transition: width 0.8s ease-in-out;
    }

    .step-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 25%;
    }

    .step-icon-container {
        width: 50px;
        height: 50px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #adb5bd;
        box-shadow: 4px 4px 8px var(--neu-shadow), -4px -4px 8px var(--neu-light);
        transition: all 0.4s ease;
    }

    .step-icon-container.active {
        background: var(--neu-primary);
        color: white;
    }

    .step-label {
        margin-top: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #adb5bd;
        text-align: center;
    }

    .step-label.active { color: #2d3436; }

    .info-box-neumorph {
        padding: 1rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: inset 3px 3px 6px var(--neu-shadow), inset -3px -3px 6px var(--neu-light);
    }

    .badge-neumorph {
        background: var(--neu-primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 30px;
    }

    .btn-neumorph-btn {
        background: #fff;
        border-radius: 10px;
        box-shadow: 4px 4px 8px var(--neu-shadow), -4px -4px 8px var(--neu-light);
        border: none;
    }

    .btn-link i {
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }

    .btn-link:hover i { color: var(--neu-primary); transform: scale(1.2); }

    #trackingNumber { word-break: break-all; margin-right: 10px; }

    /* Animasi */
    .pulse-blue { animation: pulse-blue-neu 2s infinite; }
    @keyframes pulse-blue-neu {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.6); }
        70% { box-shadow: 0 0 0 15px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }

    .dot-active { animation: pulse-dot 1.5s infinite; }
    @keyframes pulse-dot {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .tracking-list div:last-child .border-start { border-left: 2px solid transparent !important; }
</style>

<script>
function copyToClipboard() {
    const trackingNum = document.getElementById('trackingNumber').innerText;
    const icon = document.getElementById('copyIcon');
    
    navigator.clipboard.writeText(trackingNum).then(() => {
        icon.classList.replace('bi-clipboard', 'bi-clipboard-check-fill');
        icon.classList.add('text-success');
        setTimeout(() => {
            icon.classList.replace('bi-clipboard-check-fill', 'bi-clipboard');
            icon.classList.remove('text-success');
        }, 2000);
    });
}
</script>
@endsection