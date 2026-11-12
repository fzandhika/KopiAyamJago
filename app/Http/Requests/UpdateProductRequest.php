<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['slug'] = [
            'nullable',
            'string',
            'max:140',
            'alpha_dash',
            Rule::unique('products', 'slug')->ignore($this->route('product')),
        ];

        return $rules;
    }
}
