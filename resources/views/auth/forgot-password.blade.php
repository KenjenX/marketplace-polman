<x-guest-layout>
    <div class="relative min-h-[400px] flex flex-col justify-center py-4">
        <div class="flex justify-center mb-6">
            <div class="p-2 bg-white rounded-full shadow-sm border border-gray-100">
                <img src="{{ asset('assets/img/logo-polman.png') }}" class="h-16 w-16 object-contain">
            </div>
        </div>
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                Lupa Kata Sandi?
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed px-4">
                {{ __('Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan pemulihan untuk mengatur ulang kata sandi Anda.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 animate-in fade-in zoom-in duration-300">
                <div class="flex items-center p-4 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm shadow-emerald-100/50">
                    <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-emerald-500 rounded-full shadow-lg shadow-emerald-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-emerald-900">
                            Berhasil Terkirim!
                        </h3>
                        <p class="text-xs text-emerald-700 leading-tight">
                            Link reset password sudah dikirim, silakan cek email Anda (termasuk folder spam).
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="relative group">
                <x-input-label for="email" :value="__('Email Kantor / Pribadi')" class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
                
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-indigo-500">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <x-text-input id="email"
                        class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-gray-200 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all duration-200" 
                        type="email"
                        name="email"
                        :value="old('email')"
                        placeholder="contoh@polman.ac.id"
                        required autofocus />
                </div>
                
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs italic" />
            </div>

            <div class="flex flex-col space-y-3 pt-2">
                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transform transition-all active:scale-95 duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Kirim Link Reset') }}
                </button>

                <a href="{{ route('login') }}" class="text-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1 text-xs"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>