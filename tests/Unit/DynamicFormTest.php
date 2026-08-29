<?php

use App\Models\ProductType;
use App\Support\DynamicForm;

it('builds validation rules from schema', function () {
    $p = ProductType::factory()->make(['field_schema' => [
        ['name' => 'plaka', 'label' => 'Plaka', 'type' => 'text', 'required' => true],
        ['name' => 'km', 'label' => 'KM', 'type' => 'number', 'required' => false],
    ]]);

    $rules = DynamicForm::rules($p);

    expect($rules)->toHaveKey('fields.plaka')->toHaveKey('fields.km');
    expect($rules['fields.plaka'])->toContain('required');
    expect($rules['fields.km'])->toContain('nullable');
});
