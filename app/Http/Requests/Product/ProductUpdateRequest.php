<?php

namespace App\Http\Requests\Product;

class ProductUpdateRequest extends ProductStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'amount' => ['decimal:0,2'],
        ];
    }
}
