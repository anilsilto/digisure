<?php

namespace App\Console\Commands;

use App\Domain\Import\CustomerImporter;
use App\Domain\Import\ImportResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCustomers extends Command
{
    protected $signature = 'digisure:import-customers
        {file : CSV dosyasının yolu (UTF-8, virgül veya noktalı virgül ayraçlı)}
        {--dry-run : Hiçbir şey yazma, sadece ne olacağını raporla}
        {--with-policies : Aynı satırdaki poliçe sütunlarını da içe aktar}
        {--kvkk-consent : Kaydı olmayan müşterilere kvkk_consent_at = şimdi ata}
        {--report= : Atlanan satırların yazılacağı CSV yolu (varsayılan storage/app)}';

    protected $description = 'Eski panelden dışa aktarılmış müşteri CSV\'sini sisteme aktarır (TC\'den upsert, idempotent).';

    public function handle(CustomerImporter $importer): int
    {
        $path = $this->argument('file');
        if (! is_file($path)) {
            $this->error("Dosya bulunamadı: {$path}");

            return self::FAILURE;
        }

        $rows = $this->readCsv($path);
        if ($rows === []) {
            $this->error('Dosya boş veya okunamadı.');

            return self::FAILURE;
        }

        $headers = array_shift($rows);
        $map = $importer->mapHeaders($headers);

        $this->line('Tanınan sütunlar: '.(empty($map) ? '(hiçbiri!)' : implode(', ', array_keys($map))));
        if (! isset($map['tc_no'], $map['phone'])) {
            $this->error('Zorunlu sütunlar eşleşmedi: TC ve telefon. Başlıkları kontrol edin.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $options = [
            'with_policies' => (bool) $this->option('with-policies'),
            'kvkk_consent' => (bool) $this->option('kvkk-consent'),
        ];

        $tally = ['created' => 0, 'updated' => 0, 'skipped' => 0];
        $skips = [];
        $notes = [];

        DB::beginTransaction();

        foreach ($rows as $lineNo => $cells) {
            if ($this->isBlank($cells)) {
                continue;
            }

            $canonical = [];
            foreach ($map as $key => $index) {
                $canonical[$key] = $cells[$index] ?? null;
            }

            try {
                $result = $importer->importRow($canonical, $options);
            } catch (\Throwable $e) {
                $result = ImportResult::skip('hata: '.$e->getMessage());
            }

            $tally[$result->status]++;

            if ($result->isSkipped()) {
                $skips[] = ['satır' => $lineNo + 2, 'sebep' => $result->reason, 'ham' => implode(' | ', $cells)];
            } elseif ($result->note) {
                $notes[] = 'Satır '.($lineNo + 2).": {$result->note}";
            }
        }

        if ($dryRun) {
            DB::rollBack();
            $this->warn('DRY-RUN — hiçbir değişiklik kaydedilmedi.');
        } else {
            DB::commit();
        }

        $this->newLine();
        $this->table(['Sonuç', 'Adet'], [
            ['Oluşturuldu', $tally['created']],
            ['Güncellendi', $tally['updated']],
            ['Atlandı', $tally['skipped']],
        ]);

        if ($notes) {
            $this->newLine();
            $this->line('<comment>Poliçe uyarıları:</comment>');
            foreach (array_slice($notes, 0, 20) as $note) {
                $this->line('  '.$note);
            }
            if (count($notes) > 20) {
                $this->line('  ... +'.(count($notes) - 20).' tane daha');
            }
        }

        if ($skips) {
            $reportPath = $this->writeReport($skips);
            $this->newLine();
            $this->warn(count($skips).' satır atlandı. Rapor: '.$reportPath);
            foreach (array_count_values(array_column($skips, 'sebep')) as $reason => $count) {
                $this->line("  {$count}x  {$reason}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * @return list<list<string>>
     */
    private function readCsv(string $path): array
    {
        $content = file_get_contents($path);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content); // BOM

        $firstLine = strtok($content, "\r\n") ?: '';
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        $rows = [];
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        while (($cells = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = array_map(fn ($c) => $c === null ? '' : trim((string) $c), $cells);
        }
        fclose($handle);

        return $rows;
    }

    private function isBlank(array $cells): bool
    {
        return implode('', $cells) === '';
    }

    /**
     * @param  list<array{satır:int,sebep:string,ham:string}>  $skips
     */
    private function writeReport(array $skips): string
    {
        $path = $this->option('report')
            ?: storage_path('app/import-atlananlar-'.now()->format('Y-m-d-His').'.csv');

        $handle = fopen($path, 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['satır', 'sebep', 'ham veri']);
        foreach ($skips as $skip) {
            fputcsv($handle, [$skip['satır'], $skip['sebep'], $skip['ham']]);
        }
        fclose($handle);

        return $path;
    }
}
