<?php

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

it('stores tc/phone encrypted with searchable hash', function () {
    $c = Customer::create([
        'first_name' => 'A', 'last_name' => 'B',
        'tc_no' => '12345678901', 'phone' => '05322652392',
    ]);

    expect(DB::table('customers')->value('tc_no'))->not->toBe('12345678901');
    expect(Customer::whereTc('12345678901')->first()->id)->toBe($c->id);
    expect(Customer::wherePhone('0532 265 23 92')->first()->id)->toBe($c->id);
});

it('upserts by tc', function () {
    Customer::upsertByTc([
        'tc_no' => '12345678901', 'first_name' => 'A', 'last_name' => 'B', 'phone' => '05322652392',
    ]);
    $again = Customer::upsertByTc([
        'tc_no' => '12345678901', 'first_name' => 'A2', 'last_name' => 'B', 'phone' => '05322652392',
    ]);

    expect(Customer::count())->toBe(1)->and($again->first_name)->toBe('A2');
});
