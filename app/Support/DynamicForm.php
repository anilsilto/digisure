<?php

namespace App\Support;

use App\Models\ProductType;

/**
 * Ürünün field_schema JSON'undan Laravel doğrulama kuralları ve etiket haritası üretir.
 *
 * Şema girdisi: { name, label, type: text|date|number|select|tel, required: bool, options?: string[] }
 */
class DynamicForm
{
    /** @return array<string, list<string>>  ("fields.<name>" => kurallar) */
    public static function rules(ProductType $product): array
    {
        $rules = [];

        foreach ($product->field_schema ?? [] as $field) {
            $type = $field['type'] ?? 'text';

            $set = [($field['required'] ?? false) ? 'required' : 'nullable'];

            $set[] = match ($type) {
                'date' => 'date',
                'number' => 'numeric',
                'select' => 'in:'.implode(',', $field['options'] ?? []),
                default => 'string',
            };

            if (in_array($type, ['text', 'tel', 'select'], true)) {
                $set[] = 'max:255';
            }

            $rules['fields.'.$field['name']] = $set;
        }

        return $rules;
    }

    /** @return array<string, string>  (name => label) */
    public static function labels(ProductType $product): array
    {
        $labels = [];

        foreach ($product->field_schema ?? [] as $field) {
            $labels[$field['name']] = $field['label'] ?? $field['name'];
        }

        return $labels;
    }
}
