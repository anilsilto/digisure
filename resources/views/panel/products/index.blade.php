@extends('layouts.panel')

@section('title', 'Ürünler')
@section('heading', 'Ürün Yönetimi')

@section('content')
    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Anahtar</th>
                    <th class="px-4 py-3">Ad</th>
                    <th class="px-4 py-3">Alan Sayısı</th>
                    <th class="px-4 py-3">Aktif</th>
                    <th class="px-4 py-3">Sıra</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $product->key }}</td>
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ count($product->field_schema ?? []) }}</td>
                        <td class="px-4 py-3">{{ $product->is_active ? 'Evet' : 'Hayır' }}</td>
                        <td class="px-4 py-3">{{ $product->sort }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('panel.products.edit', $product->id) }}" class="text-navy hover:underline">Düzenle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
