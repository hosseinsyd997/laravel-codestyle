<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ListUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country'  => 'nullable|string|exists:countries,name',
            'currency' => 'nullable|string|exists:currencies,code',
            'sortBy'   => 'nullable|string|in:id,name,email',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('country')) {
            $this->merge([
                'country' => Str::ucfirst(strtolower($this->input('country'))),
            ]);
        }

        if ($this->has('currency')) {
            $this->merge([
                'currency' => Str::upper($this->input('currency')),
            ]);
        }
    }
}
