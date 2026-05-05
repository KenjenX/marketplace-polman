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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30'],
            'default_recipient_name' => ['nullable', 'string', 'max:255'],
            'default_province' => ['nullable', 'string', 'max:255'],
            'default_city' => ['nullable', 'string', 'max:255'],
            'default_district' => ['nullable', 'string', 'max:255'],
            'default_postal_code' => ['nullable', 'string', 'max:20'],
            'default_full_address' => ['nullable', 'string'],
        ];

        if ($user->account_type === 'company') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['contact_person'] = ['required', 'string', 'max:255'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        if ($user->account_type === 'company') {
            $user->company_name = $validated['company_name'];
            $user->contact_person = $validated['contact_person'];
            $user->name = $validated['contact_person'];
        } else {
            $user->name = $validated['name'];
        }

        $emailChanged = $user->email !== $validated['email'];

        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->default_recipient_name = $validated['default_recipient_name'] ?? null;
        $user->default_province = $validated['default_province'] ?? null;
        $user->default_city = $validated['default_city'] ?? null;
        $user->default_district = $validated['default_district'] ?? null;
        $user->default_postal_code = $validated['default_postal_code'] ?? null;
        $user->default_full_address = $validated['default_full_address'] ?? null;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAddress(Request $request)
    {
    $request->validate([
        'default_recipient_name' => ['required', 'string', 'max:255'],
        'default_province' => ['required', 'string', 'max:255'],
        'default_city' => ['required', 'string', 'max:255'],
        'default_district' => ['required', 'string', 'max:255'],
        'default_postal_code' => ['nullable', 'string', 'max:20'],
        'default_full_address' => ['required', 'string'],
    ]);

    $user = auth()->user();

    $user->update([
        'default_recipient_name' => $request->default_recipient_name,
        'default_province' => $request->default_province,
        'default_city' => $request->default_city,
        'default_district' => $request->default_district,
        'default_postal_code' => $request->default_postal_code,
        'default_full_address' => $request->default_full_address,
    ]);

    return back()->with('success', 'Alamat berhasil diperbarui');
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