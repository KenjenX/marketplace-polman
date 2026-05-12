<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email'],
        ];

        // Validasi berdasarkan tipe akun
        if ($user->account_type === 'company') {

            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];

        } else {

            $rules['name'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ADDRESS
    |--------------------------------------------------------------------------
    */
    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'default_recipient_name' => ['nullable', 'string', 'max:255'],
            'default_province_id' => ['nullable', 'string'],
            'default_province' => ['nullable', 'string'],
            'default_city_id' => ['nullable', 'string'],
            'default_city' => ['nullable', 'string'],
            'default_district_id' => ['nullable', 'string'],
            'default_district' => ['nullable', 'string'],
            'default_postal_code' => ['nullable', 'string', 'max:10'],
            'default_full_address' => ['nullable', 'string'],
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ACCOUNT
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}