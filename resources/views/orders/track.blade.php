@extends('layouts.store')

@section('content')
{{-- 🛠️ PERUBAHAN: Menambahkan baris pembungkus Grid Bootstrap agar lebar kotak putih menyusut dan berada di tengah --}}
<div class="row justify-content-center px-2">
    <div class="col-xl-9 col-lg-11 col-12">

        <div class="content-card">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h4 class="fw-bold mb-0 text-dark">Lacak Pengiriman</h4>

                <a href="{{ route('orders.show', $order->uuid) }}"
                   class="btn btn-neumorph-btn btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>

            @php
                /*
                |--------------------------------------------------------------------------
                | Tracking History
                |--------------------------------------------------------------------------
                */
                $histories = $trackingData['history'] ?? [];
                
                if(in_array($order->status, ['completed', 'delivered'])) {
                    $completedHistory = [
                        'date' => $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i'),
                        'desc' => 'Pesanan telah diterima oleh pembeli'
                    ];

                    if(count($histories) > 0) {
                        $firstDesc = strtolower($histories[0]['desc'] ?? '');
                        if(
                            str_contains($firstDesc, 'transit') ||
                            str_contains($firstDesc, 'delivery') ||
                            str_contains($firstDesc, 'dikirim') ||
                            str_contains($firstDesc, 'out for delivery')
                        ) {
                            array_shift($histories);
                        }
                    }
                    array_unshift($histories, $completedHistory);
                }

                /*
                |--------------------------------------------------------------------------
                | STEP CONFIGURATION & REALTIME MAPPING (UPDATED TO 6 STEPS)
                |--------------------------------------------------------------------------
                */
                $displaySteps = [
                    'waiting_payment' => ['label' => 'Pesanan Dibuat',   'icon' => 'bi-receipt'],
                    'paid'            => ['label' => 'Menunggu Kurir',  'icon' => 'bi-box-seam'],
                    'processed'       => ['label' => 'Dikirim',          'icon' => 'bi-truck'],
                    'shipped'         => ['label' => 'Sedang Transit',   'icon' => 'bi-geo-alt'],
                    'delivered'       => ['label' => 'Pesanan Diantarkan','icon' => 'bi-house-door'],
                    'completed'       => ['label' => 'Selesai',          'icon' => 'bi-check-circle'],
                ];

                // Pemetaan status internal DB Laravel ke indeks urutan stepper baru (0 s.d 5)
                $statusToStepMap = [
                    'pending'         => 0,
                    'waiting_payment' => 0,
                    'paid'            => 1,
                    'processed'       => 2,
                    'shipped'         => 3,
                    'delivered'       => 4,
                    'completed'       => 5
                ];

                $currentStepIndex = $statusToStepMap[$order->status] ?? 0;
                
                // Hitung persentase lebar baris biru secara presisi berdasarkan 6 langkah (pembagi berubah jadi 5)
                $totalSteps = count($displaySteps);
                $progressWidth = ($totalSteps > 1) ? ($currentStepIndex / ($totalSteps - 1)) * 100 : 0;
            @endphp

            {{-- Real-time Stepper Component --}}
            <div class="mb-5 position-relative px-3">
                <div class="stepper-progress-container">
                    {{-- Garis Dasar Abu-abu --}}
                    <div class="step-line-background"></div>
                    {{-- Garis Progres Biru Beranimasi Aktif --}}
                    <div class="step-line-active" style="width: {{ $progressWidth }}%;"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center position-relative w-100">
                    @foreach($displaySteps as $key => $step)
                        @php
                            $isActive = $currentStepIndex >= $loop->index;
                            $isCurrent = $currentStepIndex === $loop->index;
                        @endphp

                        <div class="step-item">
                            {{-- Container Icon dengan Efek Glow Pulse pada titik yang sedang berjalan --}}
                            <div class="step-icon-container {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'pulse-blue' : '' }}">
                                <i class="bi {{ $step['icon'] }}"></i>
                            </div>
                            {{-- Label Status Baru --}}
                            <div class="step-label {{ $isActive ? 'active' : '' }}">
                                {{ $step['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Main Content Panel --}}
            <div class="card-body mt-4">
                <div class="row">
                    {{-- LEFT PANEL --}}
                    <div class="col-md-4 border-end">
                        {{-- Resi --}}
                        <div class="info-box-neumorph mb-4">
                            <label class="text-muted small d-block">Nomor Resi</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold text-primary mb-0" id="trackingNumber">
                                    {{ $order->tracking_number ?? 'Belum ada resi' }}
                                </h5>
                                @if($order->tracking_number)
                                    <button class="btn btn-link p-0 text-secondary" onclick="copyToClipboard()" title="Salin Resi">
                                        <i class="bi bi-clipboard" id="copyIcon"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Kurir --}}
                        <div class="info-box-neumorph mb-4">
                            <label class="text-muted small d-block">Kurir</label>
                            <h5 class="fw-bold text-uppercase mb-0 text-dark">
                                {{ $order->courier_code ?? 'JNE' }}
                            </h5>
                        </div>

                        {{-- Status Akhir --}}
                        <div class="info-box-neumorph mb-4">
                            <label class="text-muted small d-block">Status Terakhir</label>
                            <span class="badge badge-neumorph">
                                @if(in_array($order->status, ['completed', 'delivered']))
                                    Pesanan Diterima
                                @else
                                    {{ $trackingData['summary']['status'] ?? ($order->status == 'shipped' ? 'Dalam Perjalanan' : $order->status) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- RIGHT PANEL: TIMELINE LOGISTIK --}}
                    <div class="col-md-8">
                        @if(count($histories) > 0)
                            <div class="tracking-list ms-lg-4">
                                @foreach($histories as $index => $item)
                                    @php
                                        $isCompleted = in_array($order->status, ['completed', 'delivered']);
                                    @endphp

                                    <div class="d-flex mb-4">
                                        {{-- Tanggal & Jam --}}
                                        <div class="me-3 text-center" style="min-width: 100px;">
                                            <small class="text-dark fw-bold d-block">
                                                {{ explode(' ', $item['date'])[0] }}
                                            </small>
                                            <small class="text-muted">
                                                {{ explode(' ', $item['date'])[1] ?? '' }}
                                            </small>
                                        </div>

                                        {{-- Detil Alur Konten --}}
                                        <div class="flex-grow-1 border-start ps-4 position-relative pb-2">
                                            {{-- Titik Poin Timeline --}}
                                            <div class="position-absolute shadow-sm timeline-dot {{ $index === 0 ? 'dot-active' : '' }}"
                                                 style="
                                                    left: -11px;
                                                    top: 0;
                                                    width: 22px;
                                                    height: 22px;
                                                    background: {{ $index === 0 ? ($isCompleted ? '#198754' : '#0d6efd') : '#e9ecef' }};
                                                    border-radius: 50%;
                                                    border: 4px solid #fff;
                                                    z-index: 2;
                                                 ">
                                            </div>

                                            {{-- Deskripsi Status Logistik --}}
                                            <h6 class="fw-bold mb-1 {{ $index === 0 ? ($isCompleted ? 'text-success' : 'text-primary') : 'text-dark' }}">
                                                {{ $item['desc'] }}
                                            </h6>

                                            @if($index === 0)
                                                <span class="badge {{ $isCompleted ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-primary border border-primary-subtle' }} small">
                                                    Terbaru
                                                </span>
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

    </div>
</div> {{-- 🛠️ AKHIR DARI PEMBUNGKUS GRID --}}

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

    /* Perbaikan Struktur Garis Penghubung Stepper */
    .stepper-progress-container {
        position: absolute;
        top: 25px; /* Menyelaraskan tepat di tengah-tengah tinggi icon (50px / 2) */
        left: 5%;
        right: 5%;
        height: 4px;
        transform: translateY(-50%);
        z-index: 0;
    }

    .step-line-background {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: #e9ecef;
        border-radius: 2px;
    }

    .step-line-active {
        position: absolute;
        height: 100%;
        background-color: var(--neu-primary);
        border-radius: 2px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-item {
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
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
        transition: color 0.4s ease;
    }

    .step-label.active {
        color: #2d3436;
    }

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

    .btn-link:hover i {
        color: var(--neu-primary);
        transform: scale(1.2);
    }

    #trackingNumber {
        word-break: break-all;
        margin-right: 10px;
    }

    /* Animasi Realtime Glow Pulse */
    .pulse-blue {
        animation: pulse-blue-neu 2s infinite;
        border-color: rgba(13,110,253,0.3);
    }

    @keyframes pulse-blue-neu {
        0% { box-shadow: 0 0 0 0 rgba(13,110,253,0.5), 4px 4px 8px var(--neu-shadow); }
        70% { box-shadow: 0 0 0 12px rgba(13,110,253,0), 4px 4px 8px var(--neu-shadow); }
        100% { box-shadow: 0 0 0 0 rgba(13,110,253,0), 4px 4px 8px var(--neu-shadow); }
    }

    .dot-active {
        animation: pulse-dot 1.5s infinite;
    }

    @keyframes pulse-dot {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); filter: brightness(1.1); }
        100% { transform: scale(1); }
    }

    .tracking-list div:last-child .border-start {
        border-left: 2px solid transparent !important;
    }
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