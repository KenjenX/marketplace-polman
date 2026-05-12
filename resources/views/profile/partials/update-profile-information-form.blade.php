<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui detail akun, nomor HP, dan alamat default untuk checkout.</p>
    </header>

    <form method="post" action="{{ route('profile.address.update') }}" class="mt-6 space-y-6">
        @csrf

        {{-- ... Tipe Akun, Nama, Phone, Email (Tetap seperti kode Anda) ... --}}

        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-900 mb-4">Alamat Default Checkout</h3>

            <div class="space-y-4">
                {{-- NAMA PENERIMA --}}
                <div>
                    <x-input-label for="default_recipient_name" :value="'Nama Penerima Default'" />
                    <x-text-input id="default_recipient_name" name="default_recipient_name" type="text" class="mt-1 block w-full" :value="old('default_recipient_name', $user->default_recipient_name)" />
                </div>

                {{-- PROVINSI --}}
                <div>
                    <x-input-label for="province" :value="'Provinsi Default'" />
                    <select id="province" name="default_province_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" name="default_province" id="province_name" value="{{ old('default_province', $user->default_province) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('default_province_id')" />
                </div>

                {{-- KOTA --}}
                <div>
                    <x-input-label for="city" :value="'Kota / Kabupaten Default'" />
                    <select id="city" name="default_city_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled>
                        <option value="">Pilih Kota / Kabupaten</option>
                    </select>
                    <input type="hidden" name="default_city" id="city_name" value="{{ old('default_city', $user->default_city) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('default_city_id')" />
                </div>

                {{-- KECAMATAN --}}
                <div>
                    <x-input-label for="district" :value="'Kecamatan Default'" />
                    <select id="district" name="default_district_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" name="default_district" id="district_name" value="{{ old('default_district', $user->default_district) }}">
                    <x-input-error class="mt-2" :messages="$errors->get('default_district_id')" />
                </div>

                {{-- KODE POS & ALAMAT (Tetap seperti kode Anda) --}}
                <div>
                    <x-input-label for="default_postal_code" :value="'Kode Pos Default'" />
                    <x-text-input id="default_postal_code" name="default_postal_code" type="text" class="mt-1 block w-full" :value="old('default_postal_code', $user->default_postal_code)" />
                </div>

                <div>
                    <x-input-label for="default_full_address" :value="'Alamat Lengkap Default'" />
                    <textarea id="default_full_address" name="default_full_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ old('default_full_address', $user->default_full_address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan Perubahan</x-primary-button>
        </div>
    </form>
</section>

