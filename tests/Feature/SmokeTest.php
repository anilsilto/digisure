<?php

it('boots and serves the homepage', function () {
    $this->get('/')->assertOk()->assertSee('Geleceğinizi Güvence Altına Alıyoruz');
});

it('exposes digisure config', function () {
    expect(config('digisure.enabled_insurers'))->toBe(['sompo', 'quick', 'hepiyi', 'doga']);
    expect(config('digisure.reminders.days_before'))->toBe([30, 15, 7]);
});
