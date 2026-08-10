<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'legal_name' => ['required', 'string', 'max:255'],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:100'],
            'tax_number' => ['required', 'string', 'max:100'],
            'activity_type' => ['required', Rule::in(['importer', 'exporter', 'both', 'broker'])],
            'country_code' => ['required', 'string', 'size:2'],
            'registered_address' => ['required', 'string', 'max:1000'],
            'operating_address' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'business_email' => ['required', 'email:rfc,dns', 'max:255'],
            'business_phone' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:5000'],
            'additional_information' => ['nullable', 'array'],
            'documents' => ['required', 'array', 'min:4', 'max:12'],
            'documents.*.type' => ['required', Rule::in([
                'business_registration',
                'tax_registration',
                'representative_id',
                'address_proof',
                'import_export_license',
                'bank_letter',
                'beneficial_ownership',
                'quality_certificate',
                'other',
            ])],
            'documents.*.file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
