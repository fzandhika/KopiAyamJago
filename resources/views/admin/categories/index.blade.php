@extends('layouts.admin')

@section('title', 'Kategori')
@section('heading', 'Kategori')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="font-marker text-lg text-jago">Kelompok rak</p>
            <p class="text-sm text-espresso/60">Kategori membantu pembeli menyaring katalog berdasarkan kelompok biji.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-jago btn-sm">+ Tambah Kategori</a>
    </div>

    <div class="mt-5 overflow-hidden rounded-2xl border-2 border-espresso/10 bg-[#fffaf0]/80">
        @if ($categories->isEmpty())
            <div class="px-5 py-16 text-center">
                <p class="font-marker text-xl text-espresso/50">Belum ada kategori.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-jago btn-sm mt-4">Buat Kategori Pertama</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="bg-karung-dark/30 text-[10px] uppercase tracking-widest text-espresso/50">
                        <tr>
                            <th class="px-5 py-3 font-extrabold">Urutan</th>
                            <th class="px-3 py-3 font-extrabold">Nama</th>
                            <th class="px-3 py-3 font-extrabold">Slug</th>
                            <th class="px-3 py-3 font-extrabold">Deskripsi</th>
                            <th class="px-3 py-3 font-extrabold">Produk</th>
                            <th class="px-5 py-3 text-right font-extrabold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-espresso/10">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-white/50">
                                <td class="px-5 py-3 text-xs font-extrabold text-espresso/50">{{ $category->sort_order }}</td>
                                <td class="px-3 py-3 font-extrabold text-espresso">{{ $category->name }}</td>
                                <td class="px-3 py-3 text-[11px] font-semibold text-espresso/50">{{ $category->slug }}</td>
                                <td class="max-w-xs px-3 py-3 text-xs text-espresso/60">{{ $category->description ?: '—' }}</td>
                                <td class="px-3 py-3 text-xs font-bold text-espresso">{{ $category->products_count }}</td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg border border-jago/30 bg-jago/5 px-2 py-1 text-[11px] font-bold text-jago hover:bg-jago/10">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Hapus kategori {{ $category->name }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @disabled($category->products_count > 0) title="{{ $category->products_count > 0 ? 'Masih dipakai produk' : 'Hapus kategori' }}" class="rounded-lg border border-jago/20 px-2 py-1 text-[11px] font-bold text-jago/70 hover:bg-jago/10 disabled:cursor-not-allowed disabled:opacity-30">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <p class="mt-3 text-[11px] font-semibold text-espresso/45">Kategori yang masih punya produk tidak bisa dihapus. Pindahkan produknya dulu lewat menu Produk.</p>
@endsection
