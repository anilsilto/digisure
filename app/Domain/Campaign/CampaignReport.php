<?php

namespace App\Domain\Campaign;

/**
 * "Zafir Güvence Karma" kampanyası için tek bir müşterinin durum raporu.
 * CampaignAnalyzer::analyze() tarafından üretilir; salt-okunur.
 */
final class CampaignReport
{
    /**
     * @param  list<array{key:string,label:string,points:int}>  $branches  Sahip olunan branşlar (puana göre azalan)
     * @param  list<array{label:string,branches:list<string>,addedPoints:int,newTotal:int}>  $suggestedPackages
     * @param  list<array{key:string,label:string,desc:string}>  $rewardOptions
     */
    public function __construct(
        public array $branches,
        public int $totalPoints,
        public int $threshold,
        public bool $qualified,
        public int $shortfall,
        public string $conditionText,
        public array $suggestedPackages,
        public array $rewardOptions,
    ) {}
}
