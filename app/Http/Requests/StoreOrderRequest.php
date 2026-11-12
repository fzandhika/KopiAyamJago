<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:whatsapp,transfer,qris'],
            'notes' => ['nullable', 'string', 'max:500'],
            'agree' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama penerima wajib diisi, biar paketnya nggak nyasar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi buat konfirmasi pesanan.',
            'phone.regex' => 'Nomor WhatsApp-nya kurang pas. Contoh: 081234567890.',
            'address.required' => 'Alamat pengiriman wajib diisi lengkap.',
            'payment_method.in' => 'Pilih salah satu cara bayar yang tersedia.',
            'agree.accepted' => 'Centang dulu kalau data pesanan sudah benar.',
        ];
    }
}
