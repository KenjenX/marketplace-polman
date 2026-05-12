@extends('layouts.store')

@section('content')
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
            /*
            | Jika pesanan sudah completed/delivered, timeline paling atas menjadi status diterima.
            */
            if(in_array($order->status, ['completed', 'delivered'])) {
                $completedHistory = [
                    'date' => $order->updated_at
                                ? $order->updated_at->format('Y-m-d H:i')
                                : now()->format('Y-m-d H:i'),

                    'desc' => 'Pesanan telah diterima oleh pembeli'
                ];

                // HAPUS semua status transit paling atas
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
                // Tambahkan completed di paling atas
                array_unshift($histories, $completedHistory);
            }
    @endphp
    
    {{-- Main Content --}}
    <div class="card-body">

        <div class="row">

            {{-- LEFT PANEL --}}
            <div class="col-md-4 border-end">

                {{-- Resi --}}
                <div class="info-box-neumorph mb-4">

                    <label class="text-muted small d-block">
                        Nomor Resi
                    </label>

                    <div class="d-flex align-items-center justify-content-between">

                        <h5 class="fw-bold text-primary mb-0"
                            id="trackingNumber">

                            {{ $order->tracking_number ?? 'Belum ada resi' }}

                        </h5>

                        @if($order->tracking_number)

                            <button
                                class="btn btn-link p-0 text-secondary"
                                onclick="copyToClipboard()"
                                title="Salin Resi">

                                <i class="bi bi-clipboard"
                                   id="copyIcon"></i>

                            </button>

                        @endif

                    </div>

                </div>

                {{-- Kurir --}}
                <div class="info-box-neumorph mb-4">

                    <label class="text-muted small d-block">
                        Kurir
                    </label>

                    <h5 class="fw-bold text-uppercase mb-0 text-dark">

                        {{ $order->courier_code ?? 'JNE' }}

                    </h5>

                </div>

                {{-- Status --}}
                <div class="info-box-neumorph mb-4">

                    <label class="text-muted small d-block">
                        Status Terakhir
                    </label>

                    <span class="badge badge-neumorph">

                        @if(in_array($order->status, ['completed', 'delivered']))
                            Pesanan Diterima
                        @else
                            {{ $trackingData['summary']['status']
                                ?? ($order->status == 'shipped'
                                    ? 'Dalam Perjalanan'
                                    : $order->status)
                            }}
                        @endif

                    </span>

                </div>

            </div>

            {{-- RIGHT PANEL --}}
            <div class="col-md-8">

                @if(count($histories) > 0)

                    <div class="tracking-list ms-lg-4">

                        @foreach($histories as $index => $item)

                            @php
                                $isCompleted =
                                    in_array(
                                        $order->status,
                                        ['completed', 'delivered']
                                    );
                            @endphp

                            <div class="d-flex mb-4">

                                {{-- Date --}}
                                <div class="me-3 text-center"
                                     style="min-width: 100px;">

                                    <small class="text-dark fw-bold d-block">

                                        {{ explode(' ', $item['date'])[0] }}

                                    </small>

                                    <small class="text-muted">

                                        {{ explode(' ', $item['date'])[1] ?? '' }}

                                    </small>

                                </div>

                                {{-- Timeline --}}
                                <div class="flex-grow-1 border-start ps-4 position-relative pb-2">

                                    {{-- Dot --}}
                                    <div
                                        class="position-absolute shadow-sm timeline-dot
                                            {{ $index === 0 ? 'dot-active' : '' }}"
                                        style="
                                            left: -11px;
                                            top: 0;
                                            width: 22px;
                                            height: 22px;

                                            background:
                                            {{ $index === 0
                                                ? ($isCompleted ? '#198754' : '#0d6efd')
                                                : '#e9ecef'
                                            }};

                                            border-radius: 50%;
                                            border: 4px solid #fff;
                                            z-index: 2;
                                        ">
                                    </div>

                                    {{-- Title --}}
                                    <h6 class="fw-bold mb-1

                                        {{ $index === 0
                                            ? ($isCompleted
                                                ? 'text-success'
                                                : 'text-primary')
                                            : 'text-dark'
                                        }}">

                                        {{ $item['desc'] }}

                                    </h6>

                                    {{-- Badge --}}
                                    @if($index === 0)

                                        <span class="badge
                                            {{ $isCompleted
                                                ? 'bg-success-subtle text-success border border-success-subtle'
                                                : 'bg-light text-primary border border-primary-subtle'
                                            }}
                                            small">

                                            Terbaru

                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-box-seam text-secondary-subtle"
                           style="font-size: 5rem;"></i>

                        <h5 class="text-secondary mt-3">

                            Data pelacakan belum tersedia

                        </h5>

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
        box-shadow:
            9px 9px 16px var(--neu-shadow),
            -9px -9px 16px var(--neu-light);
        border: 1px solid rgba(255,255,255,0.4);
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
        box-shadow:
            4px 4px 8px var(--neu-shadow),
            -4px -4px 8px var(--neu-light);
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

    .step-label.active {
        color: #2d3436;
    }

    .info-box-neumorph {
        padding: 1rem;
        background: #fff;
        border-radius: 12px;
        box-shadow:
            inset 3px 3px 6px var(--neu-shadow),
            inset -3px -3px 6px var(--neu-light);
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
        box-shadow:
            4px 4px 8px var(--neu-shadow),
            -4px -4px 8px var(--neu-light);
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

    .pulse-blue {
        animation: pulse-blue-neu 2s infinite;
    }

    @keyframes pulse-blue-neu {

        0% {
            box-shadow: 0 0 0 0 rgba(13,110,253,0.6);
        }

        70% {
            box-shadow: 0 0 0 15px rgba(13,110,253,0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(13,110,253,0);
        }
    }

    .dot-active {
        animation: pulse-dot 1.5s infinite;
    }

    @keyframes pulse-dot {

        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .tracking-list div:last-child .border-start {
        border-left: 2px solid transparent !important;
    }

</style>

<script>

    function copyToClipboard()
    {
        const trackingNum =
            document.getElementById('trackingNumber').innerText;

        const icon =
            document.getElementById('copyIcon');

        navigator.clipboard.writeText(trackingNum).then(() => {

            icon.classList.replace(
                'bi-clipboard',
                'bi-clipboard-check-fill'
            );

            icon.classList.add('text-success');

            setTimeout(() => {

                icon.classList.replace(
                    'bi-clipboard-check-fill',
                    'bi-clipboard'
                );

                icon.classList.remove('text-success');

            }, 2000);

        });
    }

</script>

@endsection