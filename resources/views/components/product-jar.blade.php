@props(['product', 'variant' => null, 'compact' => false])
@php
    $variant ??= $product->starter_variant;
@endphp
<div {{ $attributes->class(['mx-auto w-full', 'max-w-[180px]' => $compact, 'max-w-[230px]' => ! $compact]) }}>
    <div class="jar-hover">
        <div class="relative">
            <div class="jar-lid relative z-10 mx-auto h-5 w-[58%]"></div>

            <div class="jar-body -mt-1 aspect-[5/6]">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}"
                         alt="Biji kopi {{ $product->name }}"
                         class="absolute inset-0 h-full w-full object-cover"
                         loading="lazy">
                    <div class="absolute inset-0 bg-espresso/15"></div>
                @else
                    <div @class(['jar-coffee absolute inset-x-0 bottom-0', 'h-[68%]' => true])></div>
                @endif

                {{-- Kilau kaca --}}
                <div class="absolute -left-6 top-0 h-full w-10 rotate-12 bg-white/40 blur-md"></div>
                <div class="absolute right-2 top-3 h-16 w-2 rounded-full bg-white/50"></div>

                {{-- Stiker toples --}}
                <div class="absolute inset-0 z-[3] flex flex-col items-center justify-center px-3 text-center">
                    <div class="sticker w-full -rotate-2 px-2 py-2.5">
                        <p class="font-marker text-[11px] leading-none tracking-wide text-espresso/75">AYAM JAGO</p>
                        <p class="mt-1 font-display text-[13px] uppercase leading-[1.1] text-jago">{{ $product->name }}</p>
                        <p class="mt-1.5 font-marker text-[22px] leading-none text-jago">{{ $variant ? $variant->formattedPrice() : '—' }}</p>
                        <p class="mt-0.5 text-[9px] font-extrabold uppercase tracking-widest text-espresso/70">
                            per {{ $variant?->label ?? 'kg' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-1 h-2 w-[70%] rounded-full bg-espresso/15 blur-sm"></div>
    </div>
</div>
