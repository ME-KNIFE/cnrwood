<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:100'],
            'full_name'           => ['required', 'string', 'max:255'],
            'phone'               => ['nullable', 'string', 'max:50'],
            'address_line1'       => ['required', 'string', 'max:500'],
            'address_line2'       => ['nullable', 'string', 'max:500'],
            'city'                => ['required', 'string', 'max:100'],
            'district'            => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
            'country'             => ['required', 'string', 'max:100'],
            'is_default_shipping' => ['boolean'],
            'is_default_billing'  => ['boolean'],
        ];
    }
}
