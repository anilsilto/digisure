<?php

namespace App\Domain\Policy;

use App\Domain\Quote\QuoteRequestWorkflow;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use DomainException;
use Illuminate\Support\Facades\DB;

class PolicyService
{
    /**
     * Kabul edilen tekliften poliçe oluşturur ve talebi 'police' durumuna taşır.
     *
     * @param  array{policy_no: string, start_date: string, end_date: string, file_path?: string|null}  $attributes
     */
    public function fromAcceptedQuote(QuoteRequest $request, array $attributes): Policy
    {
        if ($request->status !== 'kabul' || ! $request->accepted_quote_id) {
            throw new DomainException('Talep henüz kabul edilmiş bir teklife sahip değil.');
        }

        return DB::transaction(function () use ($request, $attributes) {
            $quote = $request->acceptedQuote;

            $policy = Policy::create([
                'customer_id' => $request->customer_id,
                'product_type_id' => $request->product_type_id,
                'insurer' => $quote->insurer,
                'policy_no' => $attributes['policy_no'],
                'start_date' => $attributes['start_date'],
                'end_date' => $attributes['end_date'],
                'premium' => $quote->premium,
                'quote_id' => $quote->id,
                'file_path' => $attributes['file_path'] ?? null,
                'status' => 'aktif',
            ]);

            QuoteRequestWorkflow::transitionToPolicy($request);

            $this->scheduleReminders($policy);

            return $policy;
        });
    }

    /**
     * Site dışı satılan poliçeyi elle kaydeder.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function createManual(array $attributes): Policy
    {
        return DB::transaction(function () use ($attributes) {
            $product = ProductType::where('key', $attributes['urun'])->firstOrFail();

            $customer = Customer::upsertByTc([
                'tc_no' => $attributes['tc_no'],
                'first_name' => $attributes['ad'],
                'last_name' => $attributes['soyad'],
                'phone' => $attributes['telefon'],
                'email' => $attributes['eposta'] ?? null,
            ]);

            $policy = Policy::create([
                'customer_id' => $customer->id,
                'product_type_id' => $product->id,
                'insurer' => $attributes['insurer'],
                'policy_no' => $attributes['policy_no'],
                'start_date' => $attributes['start_date'],
                'end_date' => $attributes['end_date'],
                'premium' => $attributes['premium'],
                'file_path' => $attributes['file_path'] ?? null,
                'status' => 'aktif',
            ]);

            $this->scheduleReminders($policy);

            return $policy;
        });
    }

    private function scheduleReminders(Policy $policy): void
    {
        app(RenewalReminderService::class)->scheduleFor($policy);
    }
}
