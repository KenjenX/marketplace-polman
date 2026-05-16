<x-guest-layout>

    <div class="relative min-h-[450px] flex flex-col justify-center py-4">

        <!-- LOGO -->
        <div class="flex justify-center mb-6">
            <div class="p-2 bg-white rounded-full shadow-sm border border-gray-100">
                <img 
                    src="{{ asset('assets/img/logo-polman.png') }}"
                    class="h-16 w-16 object-contain"
                    alt="Logo"
                >
            </div>
        </div>

        <!-- TITLE -->
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                Atur Ulang Kata Sandi
            </h2>

            <p class="text-xs text-gray-500 italic">
                Silakan masukkan kata sandi baru Anda untuk akun Marketplace Polman.
            </p>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">

            @csrf

            <!-- TOKEN -->
            <input 
                type="hidden" 
                name="token" 
                value="{{ $request->route('token') }}"
            >

            <!-- EMAIL -->
            <input 
                type="hidden" 
                name="email" 
                value="{{ old('email', $request->email) }}"
            >

            <!-- PASSWORD -->
            <div>

                <x-input-label 
                    for="password" 
                    :value="__('Kata Sandi Baru')" 
                    class="mb-2 text-sm font-semibold text-gray-700"
                />

                <div class="relative">

                    <!-- ICON LOCK -->
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>

                    <!-- INPUT -->
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-2xl border border-gray-300 py-4 pl-12 pr-14 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"
                    >

                    <!-- EYE BUTTON -->
                    <button
                        type="button"
                        onclick="togglePassword('password', 'eyeIcon1')"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-indigo-500 transition"
                    >
                        <span id="eyeIcon1">
                            <i class="fas fa-eye"></i>
                        </span>
                    </button>

                </div>

                <x-input-error 
                    :messages="$errors->get('password')" 
                    class="mt-2 text-xs"
                />

            </div>

            <!-- CONFIRM PASSWORD -->
            <div>

                <x-input-label 
                    for="password_confirmation" 
                    :value="__('Ulangi Kata Sandi')" 
                    class="mb-2 text-sm font-semibold text-gray-700"
                />

                <div class="relative">

                    <!-- ICON CHECK -->
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-check-double"></i>
                    </div>

                    <!-- INPUT -->
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full rounded-2xl border border-gray-300 py-4 pl-12 pr-14 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"
                    >

                    <!-- EYE BUTTON -->
                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-indigo-500 transition"
                    >
                        <span id="eyeIcon2">
                            <i class="fas fa-eye"></i>
                        </span>
                    </button>

                </div>

                <x-input-error 
                    :messages="$errors->get('password_confirmation')" 
                    class="mt-2 text-xs"
                />

            </div>

            <!-- BUTTON -->
            <div class="pt-2">

                <button 
                    type="submit"
                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transform transition-all active:scale-95 duration-200"
                >

                    <i class="fas fa-save mr-2"></i>

                    {{ __('Simpan Perubahan') }}

                </button>

            </div>

        </form>

    </div>

    <!-- SHOW / HIDE PASSWORD -->
    <script>

        function togglePassword(inputId, iconId) {

            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {

                input.type = "text";

                icon.innerHTML = `<i class="fas fa-eye-slash"></i>`;

            } else {

                input.type = "password";

                icon.innerHTML = `<i class="fas fa-eye"></i>`;
            }
        }

    </script>

</x-guest-layout>