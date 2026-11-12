<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Label ramah admin untuk tiap kunci pengaturan.
     *
     * @var array<string, string>
     */
    public const LABELS = [
        'store_name' => 'Nama Toko',
        'whatsapp_number' => 'Nomor WhatsApp (format 62…)',
        'address' => 'Alamat Lengkap',
        'maps_url' => 'Link Google Maps',
        'open_hours' => 'Jam Buka',
        'instagram' => 'Instagram',
        'bank_account' => 'Rekening Transfer',
        'qris_note' => 'Catatan QRIS',
        'free_shipping_note' => 'Catatan Ongkir / Promo',
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => collect(Setting::DEFAULTS)->mapWithKeys(fn ($default, $key) => [$key => Setting::get($key, $default)]),
            'labels' => self::LABELS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = collect(array_keys(Setting::DEFAULTS))
            ->mapWithKeys(fn (string $key) => [$key => ['nullable', 'string', 'max:500']])
            ->all();

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            Setting::put($key, $value);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan toko tersimpan. Perubahan langsung tampil di website.');
    }
}
