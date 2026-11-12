@props(['label', 'value', 'max' => 5])

<div>
    <div class="flex items-center justify-between gap-3 text-[11px] font-extrabold uppercase tracking-widest">
        <span class="text-espresso/70">{{ $label }}</span>
        <span class="text-jago">{{ $value }}/{{ $max }}</span>
    </div>
    <div class="taste-bar mt-1.5">
        <span style="width: {{ ($value / $max) * 100 }}%"></span>
    </div>
</div>
