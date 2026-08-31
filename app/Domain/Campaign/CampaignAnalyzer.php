<?php

namespace App\Domain\Campaign;

/**
 * Kampanya puanlaması ve "MÜŞTERİ ANALİZ RAPORU" mantığı.
 * Girdi: müşterinin sahip olduğu kampanya branşlarının anahtar listesi.
 */
class CampaignAnalyzer
{
    public function analyze(array $branchKeys): CampaignReport
    {
        $cfg = config('digisure.campaign');
        $all = $cfg['branches'];
        $threshold = (int) $cfg['threshold'];

        $held = [];
        foreach ($all as $key => $meta) {
            if (in_array($key, $branchKeys, true)) {
                $held[$key] = ['key' => $key, 'label' => $meta['label'], 'points' => (int) $meta['points']];
            }
        }
        uasort($held, fn ($a, $b) => $b['points'] <=> $a['points']);

        $total = array_sum(array_column($held, 'points'));
        $qualified = $total >= $threshold;
        $shortfall = max(0, $threshold - $total);

        $packages = $qualified ? [] : $this->suggestPackages($cfg['packages'], $all, array_keys($held), $total, $threshold);

        $rewardOptions = [];
        foreach ($cfg['rewards'] as $key => $reward) {
            $rewardOptions[] = ['key' => $key, 'label' => $reward['label'], 'desc' => $reward['desc']];
        }

        return new CampaignReport(
            branches: array_values($held),
            totalPoints: $total,
            threshold: $threshold,
            qualified: $qualified,
            shortfall: $shortfall,
            conditionText: $this->conditionText($held, $total, $threshold, $qualified),
            suggestedPackages: $packages,
            rewardOptions: $rewardOptions,
        );
    }

    /**
     * Müşteri temsilcisi için satış söylemi. Deterministik şablon — LLM yok.
     */
    public function pitch(string $firstName, CampaignReport $report, ?string $selectedRewardLabel = null): string
    {
        $isim = trim($firstName).' Bey/Hanım';
        $kampanya = config('digisure.campaign.name');

        if ($report->qualified) {
            $odul = $selectedRewardLabel
                ? "Seçili ödülünüz: {$selectedRewardLabel}."
                : 'Menüden dilediğiniz 1 ödülü seçebilirsiniz: '.implode(', ', array_column($report->rewardOptions, 'label')).'.';

            return "{$isim}, poliçelerinizle toplam {$report->totalPoints} puana ulaşarak "
                .config('digisure.agency.name')." \"{$kampanya}\" kampanyasına hak kazandınız! {$odul}";
        }

        $oneri = $report->suggestedPackages[0] ?? null;
        if ($oneri) {
            $ekBrans = implode(' + ', $oneri['branches']);

            return "{$isim}, şu an {$report->totalPoints} puanınız var; kampanya barajı {$report->threshold} puan. "
                ."{$ekBrans} ekleyerek {$oneri['newTotal']} puana ulaşır, %10 indirimli oto bakım veya VIP lastik "
                .'hizmeti gibi ödüllerden birini hemen seçebilirsiniz. Değerlendirelim mi?';
        }

        return "{$isim}, şu an {$report->totalPoints} puanınız var; kampanya ödülü için {$report->threshold} puana "
            ."({$report->shortfall} puan daha) ulaşmanız gerekiyor.";
    }

    /**
     * @param  list<string>  $heldKeys
     * @return list<array{label:string,branches:list<string>,addedPoints:int,newTotal:int}>
     */
    private function suggestPackages(array $packages, array $all, array $heldKeys, int $total, int $threshold): array
    {
        $out = [];
        foreach ($packages as $pkg) {
            $missing = array_values(array_diff($pkg['branches'], $heldKeys));
            if ($missing === []) {
                continue; // müşteri bu paketin tüm branşlarına zaten sahip
            }
            $added = array_sum(array_map(fn ($k) => (int) $all[$k]['points'], $missing));
            if ($total + $added < $threshold) {
                continue; // paket eklense bile baraj geçilmiyor
            }
            $out[] = [
                'label' => $pkg['label'],
                'branches' => array_map(fn ($k) => $all[$k]['label'], $missing),
                'addedPoints' => $added,
                'newTotal' => $total + $added,
            ];
        }

        return $out;
    }

    private function conditionText(array $held, int $total, int $threshold, bool $qualified): string
    {
        if ($qualified) {
            foreach ($held as $branch) {
                if ($branch['points'] >= $threshold) {
                    return "{$branch['label']} poliçesi tek başına {$branch['points']} puan değerinde olduğu için "
                        ."{$threshold} puanlık kampanya barajı aşılmıştır. Müşteri menüden 1 adet ödül seçebilir.";
                }
            }

            return "Farklı branşların birleşimiyle toplam {$total} puana ulaşılarak {$threshold} puanlık kampanya "
                .'barajı aşılmıştır. Müşteri menüden 1 adet ödül seçebilir.';
        }

        if ($total === 0) {
            return 'Puan getiren bir branş bulunmuyor. Trafik poliçesi tek başına kampanya hakkı doğurmamaktadır.';
        }

        return "Toplam puan {$total} olduğu için {$threshold} puanlık kampanya barajının altında kalınmıştır.";
    }
}
