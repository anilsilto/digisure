<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductTypeController extends Controller
{
    public function index(): View
    {
        return view('panel.products.index', [
            'products' => ProductType::orderBy('sort')->get(),
        ]);
    }

    public function edit(ProductType $productType): View
    {
        return view('panel.products.edit', ['product' => $productType]);
    }

    public function update(Request $request, ProductType $productType): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort' => ['nullable', 'integer'],
            'field_schema' => ['required', 'json'],
        ]);

        $schema = json_decode($data['field_schema'], true);

        foreach ($schema as $field) {
            if (! isset($field['name'], $field['type'])) {
                throw ValidationException::withMessages([
                    'field_schema' => 'Her alan en az "name" ve "type" içermelidir.',
                ]);
            }
        }

        $productType->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
            'sort' => $data['sort'] ?? $productType->sort,
            'field_schema' => $schema,
        ]);

        return redirect()->route('panel.products.index')->with('status', 'Ürün güncellendi.');
    }
}
