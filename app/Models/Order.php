<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_code',
    'customer_name',
    'phone',
    'address',
    'status',
    'total',
    'payment_method',
    'notes',
])]
class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public const PAYMENT_METHODS = [
        'whatsapp' => 'Pesan via WhatsApp',
        'transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
    ];

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateCode(): string
    {
        do {
            $code = 'KAJ-'.now()->format('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function paymentLabel(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function formattedTotal(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }
}
