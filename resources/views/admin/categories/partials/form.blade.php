@php
    $isEdit = $category->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-2xl space-y-5">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
        <h2 class="font-display text-lg text-espresso">{{ $isEdit ? 'Ubah kategori' : 'Kategori baru' }}</h2>
        <p class="text-[11px] font-semibold text-espresso/45">Nama ini yang muncul di filter katalog dan kartu kategori beranda.</p>

        <div class="mt-4 space-y-4">
            <div>
                <label for="name" class="label-jago">Nama kategori</label>
                <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required maxlength="80" placeholder="Contoh: Arabika" class="input-jago @error('name') border-jago @enderror">
                @error('name') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="label-jago">Slug (opsional)</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug) }}" maxlength="90" placeholder="otomatis dari nama" class="input-jago @error('slug') border-jago @enderror">
                @error('slug') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="label-jago">Deskripsi singkat</label>
                <textarea id="description" name="description" rows="3" maxlength="255" placeholder="Contoh: Biji dengan keasaman cerah dan aroma buah." class="input-jago @error('description') border-jago @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </div>

            <div class="max-w-[180px]">
                <label for="sort_order" class="label-jago">Urutan tampil</label>
                <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" max="99" class="input-jago @error('sort_order') border-jago @enderror">
                <p class="mt-1 text-[10px] text-espresso/40">Angka kecil tampil lebih dulu.</p>
                @error('sort_order') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <div class="flex flex-col gap-2 sm:flex-row">
        <button type="submit" class="btn btn-jago">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kategori' }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Batal</a>
    </div>
</form>
