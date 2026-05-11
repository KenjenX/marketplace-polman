<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil & Alamat Pengiriman
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi akun dan alamat default Anda. Data ini akan langsung tersimpan ke database saat Anda menekan tombol simpan.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- INFO DASAR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone" :value="'Nomor HP'" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- SEKSI ALAMAT (DROPDOWN WILAYAH) --}}
        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-900 mb-4">Alamat Default</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama Penerima --}}
                <div class="md:col-span-2">
                    <x-input-label for="default_recipient_name" :value="'Nama Penerima'" />
                    <x-text-input id="default_recipient_name" name="default_recipient_name" type="text" class="mt-1 block w-full" :value="old('default_recipient_name', $user->default_recipient_name)" />
                </div>

                {{-- Provinsi --}}
                <div>
                    <x-input-label for="partial_province_select" :value="'Provinsi'" />
                    <select id="partial_province_select" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" name="default_province" id="partial_db_province_name" value="{{ old('default_province', $user->default_province) }}">
                    <input type="hidden" name="default_province_id" id="partial_db_province_id" value="{{ old('default_province_id', $user->default_province_id) }}">
                </div>

                {{-- Kota --}}
                <div>
                    <x-input-label for="partial_city_select" :value="'Kota / Kabupaten'" />
                    <select id="partial_city_select" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Pilih Kota</option>
                    </select>
                    <input type="hidden" name="default_city" id="partial_db_city_name" value="{{ old('default_city', $user->default_city) }}">
                    <input type="hidden" name="default_city_id" id="partial_db_city_id" value="{{ old('default_city_id', $user->default_city_id) }}">
                </div>

                {{-- Kecamatan --}}
                <div>
                    <x-input-label for="partial_district_select" :value="'Kecamatan'" />
                    <select id="partial_district_select" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" name="default_district" id="partial_db_district_name" value="{{ old('default_district', $user->default_district) }}">
                    <input type="hidden" name="default_district_id" id="partial_db_district_id" value="{{ old('default_district_id', $user->default_district_id) }}">
                </div>

                {{-- Kode Pos --}}
                <div>
                    <x-input-label for="default_postal_code" :value="'Kode Pos'" />
                    <x-text-input id="default_postal_code" name="default_postal_code" type="text" class="mt-1 block w-full" :value="old('default_postal_code', $user->default_postal_code)" />
                </div>

                {{-- Alamat Lengkap --}}
                <div class="md:col-span-2">
                    <x-input-label for="default_full_address" :value="'Alamat Lengkap (Jalan, No. Rumah)'" />
                    <textarea id="default_full_address" name="default_full_address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('default_full_address', $user->default_full_address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('success'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold">Data Berhasil Disimpan!</p>
            @endif
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provSel = document.getElementById('partial_province_select');
    const citySel = document.getElementById('partial_city_select');
    const distSel = document.getElementById('partial_district_select');

    const initialProvName = "{{ $user->default_province }}";
    const initialCityName = "{{ $user->default_city }}";
    const initialDistName = "{{ $user->default_district }}";

    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(res => res.json())
        .then(data => {
            provSel.innerHTML = '<option value="">Pilih Provinsi</option>';
            data.forEach(p => {
                let opt = new Option(p.name, p.id);
                if(p.name.toUpperCase() === String(initialProvName).toUpperCase()) opt.selected = true;
                provSel.add(opt);
            });
            if(provSel.value) provSel.dispatchEvent(new Event('change'));
        });

    provSel.addEventListener('change', function() {
        const id = this.value;
        document.getElementById('partial_db_province_id').value = id;
        document.getElementById('partial_db_province_name').value = id ? this.options[this.selectedIndex].text : "";

        citySel.innerHTML = '<option value="">Memuat...</option>';
        distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';

        if(id) {
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
                .then(res => res.json())
                .then(data => {
                    citySel.innerHTML = '<option value="">Pilih Kota</option>';
                    data.forEach(c => {
                        let opt = new Option(c.name, c.id);
                        if(c.name.toUpperCase() === String(initialCityName).toUpperCase()) opt.selected = true;
                        citySel.add(opt);
                    });
                    if(citySel.value) citySel.dispatchEvent(new Event('change'));
                });
        }
    });

    citySel.addEventListener('change', function() {
        const id = this.value;
        document.getElementById('partial_db_city_id').value = id;
        document.getElementById('partial_db_city_name').value = id ? this.options[this.selectedIndex].text : "";

        distSel.innerHTML = '<option value="">Memuat...</option>';

        if(id) {
            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${id}.json`)
                .then(res => res.json())
                .then(data => {
                    distSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(d => {
                        let opt = new Option(d.name, d.id);
                        if(d.name.toUpperCase() === String(initialDistName).toUpperCase()) opt.selected = true;
                        distSel.add(opt);
                    });
                });
        }
    });

    distSel.addEventListener('change', function() {
        const id = this.value;
        document.getElementById('partial_db_district_id').value = id;
        document.getElementById('partial_db_district_name').value = id ? this.options[this.selectedIndex].text : "";
    });
});
</script>