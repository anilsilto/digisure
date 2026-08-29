@extends('layouts.panel')

@section('title', 'Ürün Düzenle')
@section('heading', $product->name . ' — Düzenle')

@section('content')
    <form method="POST" action="{{ route('panel.products.update', $product->id) }}"
          class="max-w-2xl space-y-4 rounded-xl border border-line bg-white p-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="rounded-lg border border-danger/30 bg-danger-tint px-3 py-2 text-sm text-danger">{{ $errors->first() }}</div>
        @endif

        <label class="block text-sm">Ad
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
        </label>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked($product->is_active)> Aktif
            </label>
            <label class="text-sm">Sıra
                <input type="number" name="sort" value="{{ old('sort', $product->sort) }}" class="ml-2 w-20 rounded-lg border border-line px-2 py-1.5">
            </label>
        </div>
        <label class="block text-sm">Form Şeması (JSON)
            <textarea name="field_schema" rows="14"
                      class="mt-1 w-full rounded-lg border border-line px-2 py-1.5 font-mono text-xs">{{ old('field_schema', json_encode($product->field_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
        </label>
        <p class="text-xs text-muted">Her alan: <code>{ "name", "label", "type": text|date|number|select|tel, "required": true/false, "options": [] }</code></p>

        <button class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Kaydet</button>
    </form>
@endsection
