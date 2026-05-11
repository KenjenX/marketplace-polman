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

        {{-- TIPE AKUN --}}
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

        {{-- COMPANY --}}
        @if($user->account_type === 'company')

            <div>
                <x-input-label for="company_name" :value="'Nama Perusahaan'" />

                <x-text-input
                    id="company_name"
                    name="company_name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('company_name', $user->company_name)"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('company_name')"
                />
            </div>

            <div>
                <x-input-label for="contact_person" :value="'Nama PIC / Contact Person'" />

                <x-text-input
                    id="contact_person"
                    name="contact_person"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('contact_person', $user->contact_person)"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('contact_person')"
                />
            </div>

        @else

            <div>
                <x-input-label for="name" :value="'Nama Lengkap'" />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('name', $user->name)"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('name')"
                />
            </div>

        @endif

        {{-- PHONE --}}
        <div>
            <x-input-label for="phone" :value="'Nomor HP'" />

            <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone', $user->phone)"
            />

            <x-input-error
                class="mt-2"
                :messages="$errors->get('phone')"
            />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="'Email'" />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
            />

            <x-input-error
                class="mt-2"
                :messages="$errors->get('email')"
            />
        </div>

        {{-- ALAMAT DEFAULT --}}
        <div class="border-t pt-6">

            <h3 class="text-md font-medium text-gray-900 mb-4">
                Alamat Default Checkout
            </h3>

            <div class="space-y-4">

                {{-- NAMA PENERIMA --}}
                <div>
                    <x-input-label
                        for="default_recipient_name"
                        :value="'Nama Penerima Default'"
                    />

                    <x-text-input
                        id="default_recipient_name"
                        name="default_recipient_name"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_recipient_name', $user->default_recipient_name)"
                    />

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('default_recipient_name')"
                    />
                </div>

                {{-- PROVINSI --}}
                <div>
                    <x-input-label
                        for="province"
                        :value="'Provinsi Default'"
                    />

                    <select
                        id="province"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        style="appearance: auto; -webkit-appearance: menulist; -moz-appearance: menulist;"
                    >
                        <option value="">
                            Pilih Provinsi
                        </option>
                    </select>

                    {{-- hidden name --}}
                    <input
                        type="hidden"
                        name="default_province"
                        id="province_name"
                        value="{{ old('default_province', $user->default_province) }}"
                    />

                    {{-- hidden id --}}
                    <input
                        type="hidden"
                        name="default_province_id"
                        id="province_id"
                        value="{{ old('default_province_id', $user->default_province_id) }}"
                    />
                </div>

                {{-- KOTA --}}
                <div>
                    <x-input-label
                        for="city"
                        :value="'Kota / Kabupaten Default'"
                    />

                    <select
                        id="city"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        style="appearance: auto; -webkit-appearance: menulist; -moz-appearance: menulist;"
                    >
                        <option value="">
                            Pilih Kota / Kabupaten
                        </option>
                    </select>

                    {{-- hidden name --}}
                    <input
                        type="hidden"
                        name="default_city"
                        id="city_name"
                        value="{{ old('default_city', $user->default_city) }}"
                    />

                    {{-- hidden id --}}
                    <input
                        type="hidden"
                        name="default_city_id"
                        id="city_id_hidden"
                        value="{{ old('default_city_id', $user->default_city_id) }}"
                    />
                </div>

                {{-- KECAMATAN --}}
                <div>
                    <x-input-label
                        for="district"
                        :value="'Kecamatan Default'"
                    />

                    <select
                        id="district"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        style="appearance: auto; -webkit-appearance: menulist; -moz-appearance: menulist;"
                    >
                        <option value="">
                            Pilih Kecamatan
                        </option>
                    </select>

                    {{-- hidden name --}}
                    <input
                        type="hidden"
                        name="default_district"
                        id="district_name"
                        value="{{ old('default_district', $user->default_district) }}"
                    />

                    {{-- hidden id --}}
                    <input
                        type="hidden"
                        name="default_district_id"
                        id="district_id_hidden"
                        value="{{ old('default_district_id', $user->default_district_id) }}"
                    />
                </div>

                {{-- KODE POS --}}
                <div>

                    <x-input-label
                        for="default_postal_code"
                        :value="'Kode Pos Default'"
                    />

                    <x-text-input
                        id="default_postal_code"
                        name="default_postal_code"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('default_postal_code', $user->default_postal_code)"
                    />

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('default_postal_code')"
                    />

                </div>

                {{-- ALAMAT --}}
                <div>

                    <x-input-label
                        for="default_full_address"
                        :value="'Alamat Lengkap Default'"
                    />

                    <textarea
                        id="default_full_address"
                        name="default_full_address"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        rows="4"
                    >{{ old('default_full_address', $user->default_full_address) }}</textarea>

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('default_full_address')"
                    />

                </div>

            </div>
        </div>

        {{-- BUTTON --}}
        <div class="flex items-center gap-4">

            <x-primary-button>
                Simpan Perubahan
            </x-primary-button>

            @if (session('success'))
                <p class="text-sm text-green-600">
                    {{ session('success') }}
                </p>
            @endif

        </div>

    </form>
</section>

<script>

document.addEventListener('DOMContentLoaded', function () {

    loadProvinces();

    async function loadProvinces() {

        let response =
            await fetch('/regions/provinces');

        let data = await response.json();

        console.log(data);

        let province =
            document.getElementById('province');

        data.value.forEach(item => {

            province.innerHTML += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;
        });
    }

    // CHANGE PROVINCE
    document.getElementById('province')
    .addEventListener('change', async function () {

        let provinceId = this.value;

        let provinceName =
            this.options[this.selectedIndex].text;

        document.getElementById('province_name').value =
            provinceName;

        document.getElementById('province_id').value =
            provinceId;

        let city =
            document.getElementById('city');

        city.innerHTML =
            '<option value="">Pilih Kota / Kabupaten</option>';

        let response =
            await fetch(`/regions/cities/${provinceId}`);

        let data = await response.json();

        data.value.forEach(item => {

            city.innerHTML += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;
        });
    });

    // CHANGE CITY
    document.getElementById('city')
    .addEventListener('change', async function () {

        let cityId = this.value;

        let cityName =
            this.options[this.selectedIndex].text;

        document.getElementById('city_name').value =
            cityName;

        document.getElementById('city_id_hidden').value =
            cityId;

        let district =
            document.getElementById('district');

        district.innerHTML =
            '<option value="">Pilih Kecamatan</option>';

        let response =
            await fetch(`/regions/districts/${cityId}`);

        let data = await response.json();

        data.value.forEach(item => {

            district.innerHTML += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;
        });
    });

    // CHANGE DISTRICT
    document.getElementById('district')
    .addEventListener('change', function () {

        let districtId = this.value;

        let districtName =
            this.options[this.selectedIndex].text;

        document.getElementById('district_name').value =
            districtName;

        document.getElementById('district_id_hidden').value =
            districtId;
    });

});

</script>