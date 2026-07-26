<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerificationDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('review companies') === true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['approve', 'reject', 'suspend'])],
            'notes' => ['required_if:decision,reject,suspend', 'nullable', 'string', 'max:5000'],
            'checks' => ['nullable', 'array'],
            'checks.registration_confirmed' => ['nullable', 'boolean'],
            'checks.tax_confirmed' => ['nullable', 'boolean'],
            'checks.representative_confirmed' => ['nullable', 'boolean'],
            'checks.address_confirmed' => ['nullable', 'boolean'],
            'checks.sanctions_screened' => ['nullable', 'boolean'],
        ];
    }
}
