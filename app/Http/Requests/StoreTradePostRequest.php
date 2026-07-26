<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTradePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user?->hasApprovedCompany()) {
            return false;
        }

        return match ($this->input('type')) {
            'buy' => $user->hasRole('buyer'),
            'sell' => $user->hasRole('seller'),
            default => false,
        };
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['buy', 'sell'])],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'hs_code' => ['nullable', 'string', 'max:20', 'regex:/^[0-9.\-]+$/'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:40'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
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
