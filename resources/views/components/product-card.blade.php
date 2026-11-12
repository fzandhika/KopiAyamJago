@props(['product'])
@php
    $cheapest = $product->variants->sortBy('price')->first();
    $taste = $product->taste_profile;
@endphp
<article class="group relative flex flex-col rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/75 p-4 transition duration-300 hover:-translate-y-0.5 hover:border-emas hover:shadow-[8px_8px_0_0_rgba(200,150,46,0.28)]">
    <a href="{{ route('catalog.show', $product) }}" class="absolute inset-0 z-10 rounded-2xl focus-visible:outline-none">
        <span class="sr-only">Lihat {{ $product->name }}</span>
    </a>

    <div class="relative py-2">
        @if ($product->is_featured)
            <span class="absolute -left-1 top-0 z-20 rounded-r-md bg-jago px-2 py-1 font-marker text-[11px] tracking-wide text-stiker shadow-sm">
                Pilihan Jago
            </span>
        @endif
        <x-product-jar :product="$product" :variant="$product->starter_variant" compact />
    </div>

    <div class="mt-4 flex flex-1 flex-col">
        <div class="flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-[0.14em] text-daun">
            <span>{{ $product->typeLabel() }}</span>
            <span class="h-1 w-1 rounded-full bg-espresso/25"></span>
            <span class="text-espresso/50">{{ $product->category->name }}</span>
        </div>

        <h3 class="mt-1.5 font-display text-lg leading-tight text-espresso transition-colors group-hover:text-jago">
            {{ $product->name }}
        </h3>
        <p class="mt-1 text-xs font-semibold text-espresso/60">{{ $product->origin }} · Sangrai {{ $product->roast_level }}</p>
        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-espresso/70">{{ $taste['notes'] }}</p>
    </div>

    <div class="mt-4 flex items-end justify-between gap-2 border-t border-dashed border-espresso/15 pt-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-espresso/45">Mulai dari</p>
            <p class="font-display text-base text-jago">{{ $cheapest ? $cheapest->formattedPrice() : 'Hubungi kami' }}</p>
        </div>
        <span class="inline-flex items-center gap-1 rounded-full border border-espresso/15 bg-karung px-2.5 py-1 text-[11px] font-bold text-espresso transition-colors group-hover:border-jago group-hover:bg-jago group-hover:text-karung">
            Cobain
            <svg viewBox="0 0 16 16" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 8h9M8.5 4.5 12 8l-3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    </div>
</article>
