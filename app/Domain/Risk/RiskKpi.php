<?php

namespace App\Domain\Risk;

use App\Models\Policy;
use App\Models\RiskEvent;

/**
 * Risk panelinin acenteye somut katkısını ölçen metrikler.
 */
class RiskKpi
{
    /** Oto-dışı (çapraz satış hedefi) branşlar. */
    private const NON_AUTO = ['konut', 'isyeri', 'saglik', 'ferdi-kaza', 'seyahat'];

    /**
     * Paneli açan müşterilerin yüzde kaçı bir eksik için teklif talebi bıraktı?
     *
     * @return array{opened:int,clicked:int,rate:int}
     */
    public function awarenessClickRate(int $days = 30): array
    {
        $since = now()->subDays($days);

        $opened = RiskEvent::where('type', 'panel_goruntulendi')
            ->where('created_at', '>=', $since)
            ->distinct()->count('customer_id');

        $clicked = RiskEvent::where('type', 'talep_olusturuldu')
            ->where('created_at', '>=', $since)
            ->distinct()->count('customer_id');

        return [
            'opened' => $opened,
            'clicked' => $clicked,
            'rate' => $opened > 0 ? (int) round($clicked / $opened * 100) : 0,
        ];
    }

    /**
     * Oto-dışı bir talep bırakan müşterilerden kaçına, aynı branştan {withinDays} gün
     * içinde poliçe kesildi?
     *
     * @return array{leads:int,converted:int,rate:int}
     */
    public function crossSellConversion(int $days = 30, int $withinDays = 7): array
    {
        $leads = RiskEvent::where('type', 'talep_olusturuldu')
            ->whereIn('product_key', self::NON_AUTO)
            ->where('created_at', '>=', now()->subDays($days))
            ->get(['customer_id', 'product_key', 'created_at']);

        $converted = $leads->filter(fn ($lead) => Policy::where('customer_id', $lead->customer_id)
            ->whereRelation('productType', 'key', $lead->product_key)
            ->whereBetween('created_at', [$lead->created_at, $lead->created_at->copy()->addDays($withinDays)])
            ->exists()
        )->count();

        return [
            'leads' => $leads->count(),
            'converted' => $converted,
            'rate' => $leads->count() > 0 ? (int) round($converted / $leads->count() * 100) : 0,
        ];
    }

    /**
     * Aktif poliçelerin branş dağılımı (trafik payı ayrıca vurgulanır).
     *
     * @return array{total:int,mix:list<array{key:string,name:string,adet:int,pct:int}>,trafikPct:int}
     */
    public function portfolioMix(): array
    {
        $rows = Policy::query()
            ->where('policies.status', 'aktif')
            ->join('product_types', 'product_types.id', '=', 'policies.product_type_id')
            ->selectRaw('product_types.key as key, product_types.name as name, count(*) as adet')
            ->groupBy('product_types.key', 'product_types.name')
            ->orderByDesc('adet')
            ->get();

        $total = (int) $rows->sum('adet');

        $mix = $rows->map(fn ($r) => [
            'key' => $r->key,
            'name' => $r->name,
            'adet' => (int) $r->adet,
            'pct' => $total > 0 ? (int) round($r->adet / $total * 100) : 0,
        ])->values()->all();

        return [
            'total' => $total,
            'mix' => $mix,
            'trafikPct' => collect($mix)->firstWhere('key', 'trafik')['pct'] ?? 0,
        ];
    }
}
