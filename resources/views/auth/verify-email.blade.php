<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="mb-4">
            <a href="/">
                {{-- Menggunakan h-12 atau h-14 agar logo terlihat lebih kecil dan elegan --}}
                <x-application-logo class="h-12 w-auto fill-current text-gray-500" />
            </a>
        </div>
        
        {{-- UBAH DI SINI: sm:max-w-md diubah jadi sm:max-w-lg atau sm:max-w-xl. px-8 py-10 diubah jadi px-10 py-12 agar lebih proporsional --}}
        <div class="w-full sm:max-w-xl mt-6 px-10 py-12 bg-white shadow-2xl overflow-hidden sm:rounded-3xl border border-gray-100">
            
            <div class="flex justify-center mb-6">
                <div class="p-4 bg-blue-50 rounded-full">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Verifikasi Email Anda</h2>
            
            <div class="mb-6 text-sm text-center text-gray-600 leading-relaxed">
                {{ __('Terima kasih telah bergabung! Sebelum memulai, silakan klik tautan verifikasi yang baru saja kami kirimkan ke alamat email Anda. Belum menerima email?') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-sm font-medium text-green-700">
                    {{ __('Link verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat registrasi.') }}
                </div>
            @endif

            <div class="mt-8 flex flex-col gap-4 items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700 transition duration-300 transform hover:scale-[1.02]">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full text-center">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition-colors font-medium">
                        {{ __('Keluar / Logout') }}
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Admin Marketplace Polman. All rights reserved.
        </p>
    </div>
</x-guest-layout>