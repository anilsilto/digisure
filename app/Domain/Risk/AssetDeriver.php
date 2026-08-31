<?php

namespace App\Domain\Risk;

use App\Models\Customer;
use App\Models\CustomerAsset;
use Illuminate\Support\Str;

/**
 * Müşterinin aktif poliçelerinden ve geçmiş teklif taleplerinden varlık (araç/konut/işyeri)
 * türetir. İdempotent: tekrar çalıştığında aynı varlığı ikinci kez eklemez.
 */
class AssetDeriver
{
    /** product_type.key => varlık tipi eşlemesi. */
    private const BRANCH_ASSET = [
        'trafik' => 'arac',
        'kasko' => 'arac',
        'konut' => 'konut',
        'isyeri' => 'isyeri',
    ];

    public function sync(Customer $customer): int
    {
        $existing = $customer->assets()->get();
        $created = 0;

        foreach ($this->candidates($customer) as $candidate) {
            $already = $existing->first(fn (CustomerAsset $a) => $a->type === $candidate['type']
                && mb_strtolower($a->label) === mb_strtolower($candidate['label']));

            if ($already) {
                continue;
            }

            $asset = $customer->assets()->create([
                'type' => $candidate['type'],
                'label' => $candidate['label'],
                'meta' => $candidate['meta'],
                'source' => 'turetilmis',
                'status' => 'aktif',
            ]);
            $existing->push($asset);
            $created++;
        }

        return $created;
    }

    /**
     * @return list<array{type:string,label:string,meta:array}>
     */
    private function candidates(Customer $customer): array
    {
        $out = [];
        $seen = [];

        $policies = $customer->policies()->with('productType')->get();
        foreach ($policies as $policy) {
            $type = self::BRANCH_ASSET[$policy->productType->key] ?? null;
            if (! $type) {
                continue;
            }
            $label = $this->genericLabel($type);
            $key = $type.'|'.mb_strtolower($label);
            if (! isset($seen[$key])) {
                $seen[$key] = true;
                $out[] = ['type' => $type, 'label' => $label, 'meta' => ['kaynak' => 'police']];
            }
        }

        $requests = $customer->quoteRequests()->with(['productType', 'fields'])->get();
        foreach ($requests as $request) {
            $type = self::BRANCH_ASSET[$request->productType->key] ?? null;
            if (! $type) {
                continue;
            }
            [$label, $meta] = $this->labelFromFields($type, $request->fields->pluck('value', 'field_key'));
            $key = $type.'|'.mb_strtolower($label);
            if (! isset($seen[$key])) {
                $seen[$key] = true;
                $out[] = ['type' => $type, 'label' => $label, 'meta' => $meta + ['kaynak' => 'teklif']];
            }
        }

        return $out;
    }

    private function labelFromFields(string $type, $fields): array
    {
        if ($type === 'arac') {
            $plaka = $this->normalizePlaka($fields['plaka'] ?? null);

            return $plaka
                ? [$plaka, ['plaka' => $plaka]]
                : [$this->genericLabel($type), []];
        }

        $il = trim((string) ($fields['il'] ?? ''));
        $ilce = trim((string) ($fields['ilce'] ?? ''));

        if ($type === 'isyeri') {
            $faaliyet = trim((string) ($fields['faaliyet_konusu'] ?? ''));
            if ($faaliyet !== '') {
                return [Str::limit($faaliyet, 40, ''), ['faaliyet_konusu' => $faaliyet, 'il' => $il, 'ilce' => $ilce]];
            }
        }

        if ($il !== '') {
            $label = $ilce !== '' ? "{$ilce}/{$il}" : $il;

            return [$label, ['il' => $il, 'ilce' => $ilce]];
        }

        return [$this->genericLabel($type), []];
    }

    private function normalizePlaka(?string $raw): ?string
    {
        if (! filled($raw)) {
            return null;
        }

        return mb_strtoupper(preg_replace('/\s+/', '', trim($raw)));
    }

    private function genericLabel(string $type): string
    {
        return match ($type) {
            'arac' => 'Aracınız',
            'konut' => 'Konutunuz',
            'isyeri' => 'İşyeriniz',
            default => CustomerAsset::TYPES[$type] ?? $type,
        };
    }
}
