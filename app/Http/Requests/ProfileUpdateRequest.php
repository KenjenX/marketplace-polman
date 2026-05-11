<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'default_recipient_name' => ['nullable', 'string', 'max:255'],
            'default_province' => ['nullable', 'string', 'max:255'],
            'default_province_id' => ['nullable', 'string', 'max:255'],
            'default_city' => ['nullable', 'string', 'max:255'],
            'default_city_id' => ['nullable', 'string', 'max:255'],
            'default_district' => ['nullable', 'string', 'max:255'],
            'default_district_id' => ['nullable', 'string', 'max:255'],
            'default_postal_code' => ['nullable', 'string', 'max:255'],
            'default_full_address' => ['nullable', 'string'],
        ];
    }
}