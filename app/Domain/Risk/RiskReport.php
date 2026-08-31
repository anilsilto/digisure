<?php

namespace App\Domain\Risk;

/**
 * Bir müşterinin varlık bazlı güvence açığı raporu. RiskAnalyzer::forCustomer() üretir.
 */
final class RiskReport
{
    /**
     * @param  list<array{id:?int,type:string,typeLabel:string,label:string,items:list<array{product_key:string,label:string,severity:string,has:bool,note:?string}>,missingCount:int}>  $assets
     * @param  list<array{asset_id:?int,asset_label:string,asset_type:string,product_key:string,label:string,severity:string,note:?string,weight:int}>  $topActions
     */
    public function __construct(
        public array $assets,
        public int $riskPct,
        public string $band,
        public array $topActions,
        public bool $hasDeclaredAssets,
    ) {}

    public function bandLabel(): string
    {
        return match ($this->band) {
            'yesil' => 'Düşük risk',
            'sari' => 'Orta risk',
            default => 'Yüksek risk',
        };
    }
}
