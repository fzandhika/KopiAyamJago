<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'alpha_dash', Rule::unique('products', 'slug')],
            'origin' => ['required', 'string', 'max:80'],
            'type' => ['required', 'in:Robusta,Arabika,Luwak'],
            'roast_level' => ['required', 'in:Light,Medium,Medium-Dark,Dark'],
            'description' => ['required', 'string', 'min:20', 'max:1500'],
            'flavor_notes.bitterness' => ['required', 'integer', 'between:1,5'],
            'flavor_notes.acidity' => ['required', 'integer', 'between:1,5'],
            'flavor_notes.aroma' => ['required', 'integer', 'between:1,5'],
            'flavor_notes.body' => ['required', 'integer', 'between:1,5'],
            'flavor_notes.notes' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'variants.*.weight_gram' => ['required', 'integer', 'between:50,2000'],
            'variants.*.price' => ['required', 'integer', 'min:1000', 'max:50000000'],
            'variants.*.stock' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'variants.required' => 'Minimal satu varian berat harus diisi.',
            'variants.*.weight_gram.required' => 'Berat varian wajib diisi.',
            'variants.*.price.required' => 'Harga varian wajib diisi.',
            'image.image' => 'File yang dipilih harus berupa gambar.',
            'image.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 2 MB. Kompres dulu fotonya, ya.',
            'image.uploaded' => 'Foto gagal diunggah. Coba file yang lebih kecil atau hubungi hosting.',
        ];
    }
}
