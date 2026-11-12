<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:80'],
            'type' => ['nullable', 'in:Robusta,Arabika,Luwak'],
            'origin' => ['nullable', 'string', 'max:80'],
            'roast' => ['nullable', 'in:Light,Medium,Medium-Dark,Dark'],
            'min_price' => ['nullable', 'integer', 'min:0', 'max:50000000'],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:50000000'],
            'sort' => ['nullable', 'in:newest,cheapest,expensive,featured'],
        ];
    }
}
