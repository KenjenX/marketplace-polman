<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="account_type" :value="'Tipe Akun'" />
            <select id="account_type" name="account_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="individual" {{ old('account_type') === 'individual' ? 'selected' : '' }}>Individu</option>
                <option value="company" {{ old('account_type') === 'company' ? 'selected' : '' }}>Perusahaan</option>
            </select>
            <x-input-error :messages="$errors->get('account_type')" class="mt-2" />
        </div>

        <div class="mt-4" id="individual_fields">
            <x-input-label for="name" :value="'Nama Lengkap'" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4 hidden" id="company_fields">
            <div>
                <x-input-label for="company_name" :value="'Nama Perusahaan'" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="contact_person" :value="'Nama PIC / Contact Person'" />
                <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person')" />
                <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="'Nomor HP'" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Password'" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Konfirmasi Password'" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accountType = document.getElementById('account_type');
            const individualFields = document.getElementById('individual_fields');
            const companyFields = document.getElementById('company_fields');

            function toggleFields() {
                if (accountType.value === 'company') {
                    individualFields.classList.add('hidden');
                    companyFields.classList.remove('hidden');
                } else {
                    individualFields.classList.remove('hidden');
                    companyFields.classList.add('hidden');
                }
            }

            accountType.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
</x-guest-layout>