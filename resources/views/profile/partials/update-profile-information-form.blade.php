<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui detail akun, nomor HP, dan alamat default untuk checkout.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="account_type_display" :value="'Tipe Akun'" />
            <x-text-input
                id="account_type_display"
                type="text"
                class="mt-1 block w-full bg-gray-100"
                :value="$user->account_type === 'company' ? 'Perusahaan' : 'Individu'"
                readonly
            />
        </div>

        @if($user->account_type === 'company')
            <div>
                <x-input-label for="company_name" :value="'Nama Perusahaan'" />
                <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $user->company_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
            </div>

            <div>
                <x-input-label for="contact_person" :value="'Nama PIC / Contact Person'" />
                <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person', $user->contact_person)" />
                <x-input-error class="mt-2" :messages="$errors->get('contact_person')" />
            </div>
        @else
            <div>
                <x-input-label for="name" :value="'Nama Lengkap'" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        @endif

        <div>
            <x-input-label for="phone" :value="'Nomor HP'" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-900 mb-4">Alamat Default Checkout</h3>

            <div class="space-y-4">
                <div>
                    <x-input-label for="default_recipient_name" :value="'Nama Penerima Default'" />
                    <x-text-input
                        id="default_recipient_name"
                        name="default_recipient_name"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_recipient_name', $user->default_recipient_name)"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('default_recipient_name')" />
                </div>

                <div>
                    <x-input-label for="default_province" :value="'Provinsi Default'" />
                    <x-text-input
                        id="default_province"
                        name="default_province"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_province', $user->default_province)"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('default_province')" />
                </div>

                <div>
                    <x-input-label for="default_city" :value="'Kota / Kabupaten Default'" />
                    <x-text-input
                        id="default_city"
                        name="default_city"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_city', $user->default_city)"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('default_city')" />
                </div>

                <div>
                    <x-input-label for="default_district" :value="'Kecamatan Default'" />
                    <x-text-input
                        id="default_district"
                        name="default_district"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_district', $user->default_district)"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('default_district')" />
                </div>

                <div>
                    <x-input-label for="default_postal_code" :value="'Kode Pos Default'" />
                    <x-text-input
                        id="default_postal_code"
                        name="default_postal_code"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_postal_code', $user->default_postal_code)"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('default_postal_code')" />
                </div>

                <div>
                    <x-input-label for="default_full_address" :value="'Alamat Lengkap Default'" />
                    <textarea
                        id="default_full_address"
                        name="default_full_address"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        rows="4"
                    >{{ old('default_full_address', $user->default_full_address) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('default_full_address')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan Perubahan</x-primary-button>

            @if (session('success'))
                <p class="text-sm text-green-600">{{ session('success') }}</p>
            @endif
        </div>
    </form>
</section>