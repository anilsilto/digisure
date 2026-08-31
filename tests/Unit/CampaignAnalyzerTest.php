<?php

use App\Domain\Campaign\CampaignAnalyzer;

beforeEach(fn () => $this->analyzer = new CampaignAnalyzer);

it('kasko tek başına barajı geçer', function () {
    $report = $this->analyzer->analyze(['trafik', 'kasko']);

    expect($report->totalPoints)->toBe(5)
        ->and($report->qualified)->toBeTrue()
        ->and($report->shortfall)->toBe(0)
        ->and($report->suggestedPackages)->toBe([])
        ->and($report->conditionText)->toContain('Kasko')
        ->and($report->rewardOptions)->toHaveCount(4);
});

it('tss tek başına barajı geçer', function () {
    expect($this->analyzer->analyze(['tss'])->qualified)->toBeTrue();
});

it('trafik + dask baraj altında kalır', function () {
    $report = $this->analyzer->analyze(['trafik', 'dask']);

    expect($report->totalPoints)->toBe(1)
        ->and($report->qualified)->toBeFalse()
        ->and($report->shortfall)->toBe(4);
});

it('konut + imm kombinasyonu 5 puan yapar', function () {
    $report = $this->analyzer->analyze(['konut', 'imm']);

    expect($report->totalPoints)->toBe(5)
        ->and($report->qualified)->toBeTrue();
});

it('branş kırılımını puana göre azalan sıralar', function () {
    $report = $this->analyzer->analyze(['dask', 'konut', 'kasko']);

    expect(array_column($report->branches, 'key'))->toBe(['kasko', 'konut', 'dask'])
        ->and($report->totalPoints)->toBe(9);
});

it('baraj altındaki müşteriye barajı kapatan paket önerir', function () {
    $report = $this->analyzer->analyze(['dask']); // 1 puan

    $labels = array_column($report->suggestedPackages, 'label');
    expect($labels)->toContain('Stratejik Paket (Ev + Araç)');

    $stratejik = collect($report->suggestedPackages)->firstWhere('label', 'Stratejik Paket (Ev + Araç)');
    expect($stratejik['newTotal'])->toBe(6)
        ->and($stratejik['branches'])->toBe(['Konut Sigortası', 'İMM (Yüksek Limitli)']);
});

it('bilinmeyen branş anahtarlarını yok sayar', function () {
    $report = $this->analyzer->analyze(['kasko', 'uçan_halı']);

    expect($report->totalPoints)->toBe(5)
        ->and($report->branches)->toHaveCount(1);
});

it('hak kazanan müşteri için satış söylemi üretir', function () {
    $report = $this->analyzer->analyze(['kasko']);
    $pitch = $this->analyzer->pitch('Ahmet', $report);

    expect($pitch)->toContain('Ahmet')
        ->and($pitch)->toContain('hak kazandınız');
});

it('baraj altı müşteri için satış söyleminde paket önerisi geçer', function () {
    $report = $this->analyzer->analyze(['dask']);
    $pitch = $this->analyzer->pitch('Mehmet', $report);

    expect($pitch)->toContain('Konut Sigortası + İMM (Yüksek Limitli)')
        ->and($pitch)->toContain('6 puana');
});
