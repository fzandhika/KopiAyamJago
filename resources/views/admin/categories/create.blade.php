@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('heading', 'Tambah Kategori')

@section('content')
    <a href="{{ route('admin.categories.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-espresso/45 hover:text-jago">← Kembali ke kategori</a>
    <div class="mt-4">
        @include('admin.categories.partials.form', ['category' => $category])
    </div>
@endsection
