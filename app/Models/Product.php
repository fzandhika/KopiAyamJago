<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'origin',
    'type',
    'roast_level',
    'description',
    'flavor_notes',
    'image',
    'is_active',
    'is_featured',
])]
class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'flavor_notes' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<ProductVariant, $this> */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('weight_gram');
    }

    /** @param Builder<Product> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param Builder<Product> $query */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /** Harga termurah dari seluruh varian berat. */
    protected function minPrice(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->variants->min('price'));
    }

    /** Varian default untuk tampilan katalog (100 g bila ada). */
    protected function starterVariant(): Attribute
    {
        return Attribute::get(
            fn (): ?ProductVariant => $this->variants->firstWhere('weight_gram', 100) ?? $this->variants->first()
        );
    }

    /**
     * URL publik foto produk. Memakai route media agar tidak bergantung pada symlink storage.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            fn (): ?string => $this->image ? route('media', ['path' => $this->image]) : null
        );
    }

    /**
     * Profil rasa 1–5. Nilai disimpan pada kolom JSON flavor_notes.
     *
     * @return array{bitterness: int, acidity: int, aroma: int, body: int, notes: string}
     */
    protected function tasteProfile(): Attribute
    {
        $notes = $this->flavor_notes ?? [];

        return Attribute::get(fn (): array => [
            'bitterness' => (int) ($notes['bitterness'] ?? 3),
            'acidity' => (int) ($notes['acidity'] ?? 3),
            'aroma' => (int) ($notes['aroma'] ?? 3),
            'body' => (int) ($notes['body'] ?? 3),
            'notes' => (string) ($notes['notes'] ?? 'Kopi jujur, tanpa campuran.'),
        ]);
    }

    public function typeLabel(): string
    {
        return match (strtolower($this->type)) {
            'robusta' => 'Robusta',
            'arabika' => 'Arabika',
            'luwak' => 'Luwak',
            default => ucfirst($this->type),
        };
    }
}
