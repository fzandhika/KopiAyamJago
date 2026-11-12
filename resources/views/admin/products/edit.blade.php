@extends('layouts.admin')

@section('title', 'Edit '.$product->name)
@section('heading', 'Edit Produk')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-espresso/45 hover:text-jago">← Kembali ke daftar produk</a>
            <p class="mt-2 font-marker text-lg text-jago">{{ $product->name }}</p>
            <p class="text-sm text-espresso/60">Perubahan langsung tampil di website setelah disimpan.</p>
        </div>
        <a href="{{ route('catalog.show', $product) }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">Lihat halaman produk</a>
    </div>

    @include('admin.products.partials.form', ['product' => $product, 'categories' => $categories, 'defaultVariants' => $defaultVariants])
@endsection
