@php
    $variantRows = old('variants');

    if ($variantRows === null) {
        $variantRows = collect($defaultVariants)->map(fn ($variant) => [
            'id' => is_array($variant) ? ($variant['id'] ?? null) : $variant->id,
            'weight_gram' => is_array($variant) ? $variant['weight_gram'] : $variant->weight_gram,
            'price' => is_array($variant) ? $variant['price'] : $variant->price,
            'stock' => is_array($variant) ? $variant['stock'] : $variant->stock,
        ])->all();
    }

    $taste = old('flavor_notes', $product->taste_profile);
    $imagePath = $product->image;
    $imageExists = $imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath);
@endphp

<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <div class="grid gap-6 xl:grid-cols-[1fr_340px] xl:items-start">
        <div class="space-y-6">
            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                <h2 class="font-display text-lg text-espresso">Info biji kopi</h2>
                <p class="text-[11px] font-semibold text-espresso/45">Nama dan asal akan tampil di kartu katalog dan halaman detail.</p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="label-jago">Nama produk</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Arabica Toraja" class="input-jago @error('name') border-jago @enderror">
                        @error('name') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="label-jago">Slug URL (opsional)</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug', $product->slug) }}" placeholder="otomatis dari nama" class="input-jago @error('slug') border-jago @enderror">
                        @error('slug') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="label-jago">Kategori</label>
                        <select id="category_id" name="category_id" required class="input-jago @error('category_id') border-jago @enderror">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="origin" class="label-jago">Daerah asal</label>
                        <input id="origin" name="origin" type="text" value="{{ old('origin', $product->origin) }}" required placeholder="Contoh: Toraja, Sulawesi Selatan" class="input-jago @error('origin') border-jago @enderror">
                        @error('origin') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="label-jago">Jenis</label>
                        <select id="type" name="type" required class="input-jago @error('type') border-jago @enderror">
                            @foreach (['Robusta', 'Arabika', 'Luwak'] as $type)
                                <option value="{{ $type }}" @selected(old('type', $product->type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="roast_level" class="label-jago">Tingkat sangrai</label>
                        <select id="roast_level" name="roast_level" required class="input-jago @error('roast_level') border-jago @enderror">
                            @foreach (['Light', 'Medium', 'Medium-Dark', 'Dark'] as $roast)
                                <option value="{{ $roast }}" @selected(old('roast_level', $product->roast_level) === $roast)>{{ $roast }}</option>
                            @endforeach
                        </select>
                        @error('roast_level') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="label-jago">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" required minlength="20" maxlength="1500" placeholder="Ceritakan rasanya, cocok untuk seduh apa, dan kenapa biji ini dipilih." class="input-jago @error('description') border-jago @enderror">{{ old('description', $product->description) }}</textarea>
                        <p class="mt-1 text-[10px] text-espresso/40">Minimal 20 karakter. Tulis apa adanya, jangan pakai klaim berlebihan.</p>
                        @error('description') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6">
                <h2 class="font-display text-lg text-espresso">Profil rasa</h2>
                <p class="text-[11px] font-semibold text-espresso/45">Skala 1–5. Dipakai untuk bar rasa di halaman detail.</p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (['bitterness' => 'Pahit', 'acidity' => 'Asam', 'aroma' => 'Aroma', 'body' => 'Body'] as $key => $label)
                        <div>
                            <label for="flavor_{{ $key }}" class="label-jago">{{ $label }}</label>
                            <select id="flavor_{{ $key }}" name="flavor_notes[{{ $key }}]" class="input-jago">
                                @foreach (range(1, 5) as $score)
                                    <option value="{{ $score }}" @selected((int) ($taste[$key] ?? 3) === $score)>{{ $score }} / 5</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label for="flavor_notes" class="label-jago">Catatan rasa singkat</label>
                    <input id="flavor_notes" name="flavor_notes[notes]" type="text" value="{{ $taste['notes'] ?? '' }}" maxlength="255" placeholder="Contoh: Cokelat, rempah, aftertaste manis" class="input-jago">
                </div>
            </section>

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5 sm:p-6" data-variant-repeater>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="font-display text-lg text-espresso">Varian berat & harga</h2>
                        <p class="text-[11px] font-semibold text-espresso/45">Harga di sini yang dipakai di katalog dan checkout.</p>
                    </div>
                    <button type="button" data-variant-add class="btn btn-outline btn-sm">+ Tambah baris</button>
                </div>

                <div class="mt-4 space-y-2" data-variant-rows>
                    @foreach ($variantRows as $index => $variant)
                        <div class="grid grid-cols-[1fr_1fr_0.8fr_auto] items-end gap-2 rounded-xl border border-espresso/10 bg-white/40 p-3" data-variant-row>
                            <div>
                                <label class="label-jago">Berat (gram)</label>
                                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">
                                <input type="number" name="variants[{{ $index }}][weight_gram]" value="{{ $variant['weight_gram'] }}" min="50" max="2000" step="10" required class="input-jago py-2 text-sm">
                            </div>
                            <div>
                                <label class="label-jago">Harga (Rp)</label>
                                <input type="number" name="variants[{{ $index }}][price]" value="{{ $variant['price'] }}" min="1000" step="500" required class="input-jago py-2 text-sm">
                            </div>
                            <div>
                                <label class="label-jago">Stok</label>
                                <input type="number" name="variants[{{ $index }}][stock]" value="{{ $variant['stock'] }}" min="0" step="1" required class="input-jago py-2 text-sm">
                            </div>
                            <button type="button" data-variant-remove class="mb-0.5 grid h-9 w-9 place-items-center rounded-lg border-2 border-jago/20 text-jago/70 hover:bg-jago/10" aria-label="Hapus varian">✕</button>
                        </div>
                    @endforeach
                </div>

                <template>
                    <div class="grid grid-cols-[1fr_1fr_0.8fr_auto] items-end gap-2 rounded-xl border border-espresso/10 bg-white/40 p-3" data-variant-row>
                        <div>
                            <label class="label-jago">Berat (gram)</label>
                            <input type="hidden" name="variants[__i__][id]" value="">
                            <input type="number" name="variants[__i__][weight_gram]" value="100" min="50" max="2000" step="10" required class="input-jago py-2 text-sm">
                        </div>
                        <div>
                            <label class="label-jago">Harga (Rp)</label>
                            <input type="number" name="variants[__i__][price]" value="25000" min="1000" step="500" required class="input-jago py-2 text-sm">
                        </div>
                        <div>
                            <label class="label-jago">Stok</label>
                            <input type="number" name="variants[__i__][stock]" value="20" min="0" step="1" required class="input-jago py-2 text-sm">
                        </div>
                        <button type="button" data-variant-remove class="mb-0.5 grid h-9 w-9 place-items-center rounded-lg border-2 border-jago/20 text-jago/70 hover:bg-jago/10" aria-label="Hapus varian">✕</button>
                    </div>
                </template>

                @error('variants') <p class="mt-3 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                @error('variants.*.weight_gram') <p class="mt-2 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
                @error('variants.*.price') <p class="mt-2 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                <h2 class="font-display text-lg text-espresso">Foto produk</h2>
                <p class="mt-1 text-[11px] leading-relaxed font-semibold text-espresso/45">
                    Opsional. Tanpa foto, katalog memakai ilustrasi toples CSS bawaan.
                </p>

                @if ($imageExists)
                    <img src="{{ $product->image_url }}" alt="Foto {{ $product->name }}" class="mt-3 aspect-square w-full rounded-xl border border-espresso/10 object-cover">
                @else
                    <div class="mt-3 grid aspect-square w-full place-items-center rounded-xl border-2 border-dashed border-espresso/15 bg-karung-dark/20">
                        <p class="px-4 text-center font-marker text-sm text-espresso/40">Belum ada foto.<br>Ilustrasi toples dipakai.</p>
                    </div>
                @endif

                <div data-image-preview-wrap class="mt-3 hidden">
                    <p class="mb-1 text-[10px] font-extrabold uppercase tracking-widest text-jago">Pratinjau foto baru</p>
                    <img data-image-preview alt="Pratinjau foto produk baru" class="aspect-square w-full rounded-xl border-2 border-jago/40 object-cover">
                    <p data-image-error class="mt-1 hidden text-[11px] font-semibold text-jago"></p>
                </div>

                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-image-input class="mt-3 block w-full text-[11px] font-semibold text-espresso/60 file:mr-2 file:rounded-lg file:border-0 file:bg-sangrai file:px-3 file:py-2 file:text-[11px] file:font-bold file:text-karung">
                <p class="mt-1 text-[10px] text-espresso/40">JPG, PNG, atau WebP. Maks 2 MB. Rasio kotak paling rapi. Foto baru tersimpan setelah kamu klik Simpan.</p>
                @error('image') <p class="mt-1 text-xs font-semibold text-jago">{{ $message }}</p> @enderror
            </section>

            <section class="rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80 p-5">
                <h2 class="font-display text-lg text-espresso">Tampilan</h2>

                <label class="mt-3 flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="mt-0.5 h-4 w-4 rounded border-2 border-espresso/30 text-jago focus:ring-emas">
                    <span>
                        <span class="block text-xs font-extrabold text-espresso">Tayang di katalog</span>
                        <span class="block text-[10px] text-espresso/50">Matikan kalau stok habis total.</span>
                    </span>
                </label>

                <label class="mt-3 flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="mt-0.5 h-4 w-4 rounded border-2 border-espresso/30 text-jago focus:ring-emas">
                    <span>
                        <span class="block text-xs font-extrabold text-espresso">Pilihan Jago</span>
                        <span class="block text-[10px] text-espresso/50">Muncul di beranda dan dapat pita di kartu.</span>
                    </span>
                </label>
            </section>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn btn-jago w-full">{{ $product->exists ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline w-full">Batal</a>
            </div>
        </aside>
    </div>
</form>
