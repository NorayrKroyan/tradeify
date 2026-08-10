<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTradePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user?->hasApprovedCompany()) {
            return false;
        }

        if ($user->hasAnyRole(['super-admin', 'administrator'])) {
            return in_array($this->input('type'), ['buy', 'sell', 'broker'], true);
        }

        return match ($this->input('type')) {
            'buy' => $user->hasRole('buyer'),
            'sell' => $user->hasRole('seller'),
            'broker' => $user->hasRole('broker'),
            default => false,
        };
    }

    public function rules(): array
    {
        $isBroker = fn (): bool => $this->input('type') === 'broker';
        $commissionNeedsValue = fn (): bool => $isBroker()
            && in_array($this->input('broker_commission_type'), ['percentage', 'fixed'], true);

        return [
            'type' => ['required', Rule::in(['buy', 'sell', 'broker'])],
            'broker_direction' => [
                Rule::requiredIf($isBroker),
                'nullable',
                Rule::in(['seeking_buyer', 'seeking_seller']),
            ],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'hs_code' => ['nullable', 'string', 'max:20', 'regex:/^[0-9.\-]+$/'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:40'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'target_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'broker_commission_type' => [
                Rule::requiredIf($isBroker),
                'nullable',
                Rule::in(['percentage', 'fixed', 'negotiable']),
            ],
            'broker_commission_value' => [
                Rule::requiredIf($commissionNeedsValue),
                'nullable',
                'numeric',
                'min:0',
            ],
            'price_terms' => ['nullable', 'string', 'max:255'],
            'incoterm' => ['nullable', 'string', 'max:20'],
            'origin_country_code' => ['nullable', 'string', 'size:2'],
            'destination_country_code' => ['nullable', 'string', 'size:2'],
            'minimum_order_quantity' => ['nullable', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'custom_fields' => ['nullable', 'array', 'max:20'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
