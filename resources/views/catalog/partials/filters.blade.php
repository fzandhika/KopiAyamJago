@php
    $roasts = ['Light' => 'Light', 'Medium' => 'Medium', 'Medium-Dark' => 'Medium-Dark', 'Dark' => 'Dark'];
@endphp

<form action="{{ route('catalog.index') }}" method="GET" class="space-y-5">
    <div>
        <p class="label-jago">Jenis Biji</p>
        <div class="flex flex-wrap gap-1.5">
            @foreach (['' => 'Semua', 'Robusta' => 'Robusta', 'Arabika' => 'Arabika', 'Luwak' => 'Luwak'] as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="{{ $value }}" class="peer sr-only" @checked(($filters['type'] ?? '') === $value)>
                    <span class="inline-block rounded-full border-2 border-espresso/15 px-3 py-1 text-xs font-bold text-espresso/70 transition peer-checked:border-sangrai peer-checked:bg-sangrai peer-checked:text-karung">
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label for="origin" class="label-jago">Asal Daerah</label>
        <select id="origin" name="origin" class="input-jago text-sm">
            <option value="">Semua daerah</option>
            @foreach ($origins as $origin)
                <option value="{{ $origin }}" @selected(($filters['origin'] ?? '') === $origin)>{{ $origin }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="roast" class="label-jago">Tingkat Sangrai</label>
        <select id="roast" name="roast" class="input-jago text-sm">
            <option value="">Semua tingkat</option>
            @foreach ($roasts as $value => $label)
                <option value="{{ $value }}" @selected(($filters['roast'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="label-jago">Rentang Harga (per varian)</p>
        <div class="flex items-center gap-2">
            <input type="number" name="min_price" min="0" step="5000" value="{{ $filters['min_price'] ?? '' }}" placeholder="Rp min" class="input-jago text-xs" aria-label="Harga minimum">
            <span class="text-espresso/40">–</span>
            <input type="number" name="max_price" min="0" step="5000" value="{{ $filters['max_price'] ?? '' }}" placeholder="Rp max" class="input-jago text-xs" aria-label="Harga maksimum">
        </div>
    </div>

    <div class="flex flex-col gap-2 pt-1">
        <button type="submit" class="btn btn-jago w-full">Terapkan Filter</button>
        <a href="{{ route('catalog.index') }}" class="text-center text-xs font-bold text-espresso/50 underline underline-offset-4 hover:text-jago">Reset semua</a>
    </div>
</form>
