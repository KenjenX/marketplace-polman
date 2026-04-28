<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_type' => ['required', 'in:individual,company'],
            'name' => ['nullable', 'string', 'max:255', 'required_if:account_type,individual'],
            'phone' => ['required', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255', 'required_if:account_type,company'],
            'contact_person' => ['nullable', 'string', 'max:255', 'required_if:account_type,company'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $resolvedName = $request->account_type === 'company'
            ? $request->contact_person
            : $request->name;

        $user = User::create([
            'name' => $resolvedName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'account_type' => $request->account_type,
            'phone' => $request->phone,
            'company_name' => $request->account_type === 'company' ? $request->company_name : null,
            'contact_person' => $request->account_type === 'company' ? $request->contact_person : null,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login dengan akun yang baru dibuat.');
    }
}
