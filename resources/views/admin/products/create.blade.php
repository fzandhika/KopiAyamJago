@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('heading', 'Tambah Produk')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-espresso/45 hover:text-jago">← Kembali ke daftar produk</a>
            <p class="mt-2 font-marker text-lg text-jago">Biji baru di rak</p>
            <p class="text-sm text-espresso/60">Isi data dengan jujur. Harga dan stok bisa diubah kapan saja.</p>
        </div>
    </div>

    @include('admin.products.partials.form', ['product' => $product, 'categories' => $categories, 'defaultVariants' => $defaultVariants])
@endsection
