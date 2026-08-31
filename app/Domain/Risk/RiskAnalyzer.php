<?php

namespace App\Domain\Risk;

use App\Models\Customer;
use App\Models\CustomerAsset;

/**
 * Müşterinin varlıklarına karşılık sahip olduğu / eksik olan güvenceleri hesaplar,
 * tek bir risk yüzdesi ve renk bandı üretir.
 */
class RiskAnalyzer
{
    /** Kampanya branş anahtarı => risk (product_type) anahtarı. */
    private const CAMPAIGN_MAP = [
        'tss' => 'saglik',
        'ferdi_kaza' => 'ferdi-kaza',
        'kasko' => 'kasko',
        'konut' => 'konut',
        'trafik' => 'trafik',
    ];

    public function forCustomer(Customer $customer): RiskReport
    {
        $rules = config('digisure.risk.rules');
        $bands = config('digisure.risk.bands');
        $held = $this->heldBranches($customer);

        $declared = $customer->assets()->active()
            ->whereIn('type', array_keys(CustomerAsset::TYPES))
            ->get();

        // Değerlendirilecek varlıklar: beyan edilenler + her müşteride örtük "kişi".
        $blocks = [];
        foreach ($declared as $asset) {
            $blocks[] = $this->block($asset->id, $asset->type, $asset->typeLabel(), $asset->label, $rules[$asset->type] ?? [], $held);
        }
        $blocks[] = $this->block(null, 'kisi', 'Kendiniz', trim($customer->first_name.' '.$customer->last_name) ?: 'Kendiniz', $rules['kisi'] ?? [], $held);

        [$totalWeight, $missingWeight, $actions] = $this->tally($blocks, $rules, $held);

        $pct = $totalWeight > 0 ? (int) round($missingWeight / $totalWeight * 100) : 0;
        usort($actions, fn ($a, $b) => $b['weight'] <=> $a['weight']);

        return new RiskReport(
            assets: $blocks,
            riskPct: $pct,
            band: $pct <= $bands['yesil'] ? 'yesil' : ($pct <= $bands['sari'] ? 'sari' : 'kirmizi'),
            topActions: array_values($actions),
            hasDeclaredAssets: $declared->isNotEmpty(),
        );
    }

    /**
     * @return list<string>
     */
    private function heldBranches(Customer $customer): array
    {
        $fromPolicies = $customer->policies()
            ->where('status', 'aktif')
            ->with('productType')
            ->get()
            ->pluck('productType.key')
            ->filter()
            ->all();

        $fromCampaign = collect($customer->campaignProfile?->branches ?? [])
            ->map(fn ($key) => self::CAMPAIGN_MAP[$key] ?? null)
            ->filter()
            ->all();

        return array_values(array_unique([...$fromPolicies, ...$fromCampaign]));
    }

    /**
     * @param  array<string,array{label:string,weight:int,severity:string,note?:string}>  $expected
     * @param  list<string>  $held
     */
    private function block(?int $id, string $type, string $typeLabel, string $label, array $expected, array $held): array
    {
        $items = [];
        $missing = 0;
        foreach ($expected as $productKey => $meta) {
            $has = in_array($productKey, $held, true);
            $items[] = [
                'product_key' => $productKey,
                'label' => $meta['label'],
                'severity' => $meta['severity'],
                'has' => $has,
                'note' => $meta['note'] ?? null,
            ];
            if (! $has) {
                $missing++;
            }
        }

        return [
            'id' => $id,
            'type' => $type,
            'typeLabel' => $typeLabel,
            'label' => $label,
            'items' => $items,
            'missingCount' => $missing,
        ];
    }

    /**
     * @return array{0:int,1:int,2:list<array<string,mixed>>}
     */
    private function tally(array $blocks, array $rules, array $held): array
    {
        $total = 0;
        $missing = 0;
        $actions = [];

        foreach ($blocks as $block) {
            foreach ($rules[$block['type']] ?? [] as $productKey => $meta) {
                $total += $meta['weight'];
                if (in_array($productKey, $held, true)) {
                    continue;
                }
                $missing += $meta['weight'];
                $actions[] = [
                    'asset_id' => $block['id'],
                    'asset_label' => $block['label'],
                    'asset_type' => $block['type'],
                    'product_key' => $productKey,
                    'label' => $meta['label'],
                    'severity' => $meta['severity'],
                    'note' => $meta['note'] ?? null,
                    'weight' => $meta['weight'],
                ];
            }
        }

        return [$total, $missing, $actions];
    }
}
