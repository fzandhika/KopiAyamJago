<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'product_variant_id',
    'product_name',
    'weight_gram',
    'grind_type',
    'qty',
    'price',
])]
class OrderItem extends Model
{
    use HasFactory;

    public const GRIND_TYPES = [
        'whole' => 'Biji Utuh',
        'coarse' => 'Kasar (French Press)',
        'medium' => 'Medium (V60 / Moka)',
        'fine' => 'Halus (Espresso / Tubruk)',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<ProductVariant, $this> */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    protected function subtotal(): Attribute
    {
        return Attribute::get(fn (): int => $this->price * $this->qty);
    }

    public function grindLabel(): string
    {
        return self::GRIND_TYPES[$this->grind_type] ?? ucfirst($this->grind_type);
    }
}
