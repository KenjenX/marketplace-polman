<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'phone' => ['required', 'string', 'max:30'],

            // Alamat default
            'default_recipient_name' => ['nullable', 'string', 'max:255'],

            'default_province_id' => ['nullable', 'string', 'max:255'],
            'default_province' => ['nullable', 'string', 'max:255'],
            
            'default_city_id' => ['nullable', 'string', 'max:255'],
            'default_city' => ['nullable', 'string', 'max:255'],

            'default_district_id' => ['nullable', 'string', 'max:255'],
            'default_district' => ['nullable', 'string', 'max:255'],

            'default_postal_code' => ['nullable', 'string', 'max:20'],
            'default_full_address' => ['nullable', 'string'],
        ];

        // Validasi tambahan untuk tipe akun perusahaan
        if ($user->account_type === 'company') {
            $rules['company_name'] = [
                'required',
                'string',
                'max:255'
            ];

            $rules['contact_person'] = [
                'required',
                'string',
                'max:255'
            ];
        } else {
            $rules['name'] = [
                'required',
                'string',
                'max:255'
            ];
        }

        $validated = $request->validate($rules);

        // Update data user berdasarkan tipe akun (Perusahaan atau Individu)
        if ($user->account_type === 'company') {
            $user->company_name = $validated['company_name'];
            $user->contact_person = $validated['contact_person'];
            $user->name = $validated['contact_person'];
        } else {
            $user->name = $validated['name'];
        }

        // Cek apakah email berubah untuk menentukan apakah perlu reset verifikasi email atau tidak
        $emailChanged = $user->email !== $validated['email'];

        // Update data umum seperti email, phone, dan alamat default
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Update alamat default
        $user->default_recipient_name = $validated['default_recipient_name'] ?? null;

        // Provinsi, Kota, dan Kecamatan disimpan baik nama maupun ID-nya untuk memudahkan integrasi dengan layanan pengiriman
        $user->default_province_id = $validated['default_province_id'] ?? null;
        $user->default_province = $validated['default_province'] ?? null;
        
        $user->default_city_id = $validated['default_city_id'] ?? null;
        $user->default_city = $validated['default_city'] ?? null;
        
        $user->default_district_id = $validated['default_district_id'] ?? null;
        $user->default_district = $validated['default_district'] ?? null;
        
        $user->default_postal_code = $validated['default_postal_code'] ?? null;
        $user->default_full_address = $validated['default_full_address'] ?? null;

        // Jika email berubah, reset status verifikasi email
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAddress(Request $request)
    {
        // PERBAIKAN: recipient_name wajib isi, yang lain nullable agar tidak diam-diam error
        $validated = $request->validate([
            'default_recipient_name' => ['required', 'string', 'max:255'],
            'default_province_id'    => ['nullable', 'string', 'max:255'],
            'default_province'       => ['nullable', 'string', 'max:255'],
            'default_city_id'        => ['nullable', 'string', 'max:255'],
            'default_city'           => ['nullable', 'string', 'max:255'],
            'default_district_id'    => ['nullable', 'string', 'max:255'],
            'default_district'       => ['nullable', 'string', 'max:255'],
            'default_postal_code'    => ['nullable', 'string', 'max:20'],
            'default_full_address'   => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        // Jauh lebih bersih dan rapi
        $user->update($validated);

        // PERBAIKAN: Gunakan status-alamat agar trigger popup di blade
        return back()->with('status-alamat', 'alamat-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}