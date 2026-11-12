<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    /** Nilai bawaan bila belum diatur di panel admin. */
    public const DEFAULTS = [
        'store_name' => 'Kopi Ayam Jago',
        'whatsapp_number' => '6281234567890',
        'address' => 'Gang H. Saidi No. 12, RT 04/RW 03, Kebayoran Lama, Jakarta Selatan',
        'maps_url' => 'https://maps.app.goo.gl/FEEZ8LVz4QcCnSV19',
        'open_hours' => 'Setiap hari, 07.00 – 21.00 WIB',
        'instagram' => '@kopiayamjago',
        'bank_account' => 'BCA 1234567890 a.n. Kopi Ayam Jago',
        'qris_note' => 'Scan QRIS di meja kasir atau minta kode QR via WhatsApp.',
        'free_shipping_note' => 'Gratis ongkir area Jakarta untuk belanja di atas Rp 250.000.',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = Cache::rememberForever('settings.all', fn (): array => self::query()->pluck('value', 'key')->all());

        return $settings[$key] ?? $default ?? self::DEFAULTS[$key] ?? null;
    }

    public static function put(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    public static function whatsappLink(string $message = ''): string
    {
        $number = preg_replace('/\D+/', '', (string) self::get('whatsapp_number'));

        return 'https://wa.me/'.$number.($message !== '' ? '?text='.rawurlencode($message) : '');
    }
}
