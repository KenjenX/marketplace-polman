<x-mail::message>
{{-- Header Custom dengan Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <a href="{{ config('app.url') }}" style="display: inline-block;">
        <img src="https://i.ibb.co.com/xKyv0Cqj/image-2026-05-12-125032332-removebg-preview.png" style="width: 150px; height: auto;" class="logo" alt="Marketplace Polman Logo">
    </a>
</div>

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Halo, Rekan Polman!')
@endif
@endif

{{-- Intro Lines --}}
<div style="color: #555; line-height: 1.6;">
@foreach ($introLines as $line)
{{ $line }}
@endforeach
</div>

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success' => 'success',
        'error' => 'error',
        default => 'primary', // Ini akan mengambil warna biru di theme default
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
<div style="color: #555; line-height: 1.6; margin-top: 20px;">
@foreach ($outroLines as $line)
{{ $line }}
@endforeach
</div>

{{-- Salutation --}}
<div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Salam hangat,')<br>
**Tim IT {{ config('app.name') }}**
@endif
</div>

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
<div style="font-size: 11px; color: #999;">
@lang(
    "Jika tombol \":actionText\" tidak berfungsi, silakan salin tautan di bawah ini ke browser Anda:",
    ['actionText' => $actionText]
)
<br>
<span class="break-all" style="color: #0d6efd;">[{{ $actionUrl }}]({{ $actionUrl }})</span>
</div>
</x-slot:subcopy>
@endisset
</x-mail::message>