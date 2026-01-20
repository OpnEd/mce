<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveExternalOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // La seguridad real (firmas) ya la maneja tu middleware VerifyExternalSignature
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'external_order_id'           => 'required|string',
            'created_at'                  => 'required|date',
            'customer.name'               => 'required|string|max:255',
            'customer.phone'              => 'required|string',
            'customer.email'              => 'nullable|email',
            'customer.address'            => 'required|string',
            'customer.lat'                => 'nullable|numeric',
            'customer.lng'                => 'nullable|numeric',
            'items'                       => 'required|array|min:1',
            'items.*.sku'                 => 'nullable|string',
            'items.*.product_id'          => 'nullable|integer',
            'items.*.name'                => 'required|string',
            'items.*.qty'                 => 'required|integer|min:1',
            'items.*.price'               => 'required|integer|min:0',
            'meta.notify_radius_m'        => 'nullable|integer',
            'meta.notes'                  => 'nullable|string',
            'meta.payment_method'         => 'nullable|string',
            'meta.estimated_total'        => 'nullable|integer',
        ];
    }
}
