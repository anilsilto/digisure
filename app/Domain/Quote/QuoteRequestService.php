<?php

namespace App\Domain\Quote;

use App\Domain\Insurer\QuoteProviderManager;
use App\Models\Customer;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class QuoteRequestService
{
    public function __construct(private readonly QuoteProviderManager $providers) {}

    /**
     * @param  array<string, string|null>  $fields  field_key => value
     * @param  array<string, mixed>  $customerAttrs  tc_no, first_name, last_name, phone, email
     */
    public function create(
        ProductType $product,
        array $fields,
        array $customerAttrs,
        string $source,
        bool $kvkk,
    ): QuoteRequest {
        if (! $kvkk) {
            throw ValidationException::withMessages([
                'kvkk' => 'KVKK aydınlatma onayı gereklidir.',
            ]);
        }

        return DB::transaction(function () use ($product, $fields, $customerAttrs, $source) {
            $customer = Customer::upsertByTc([
                ...$customerAttrs,
                'kvkk_consent_at' => now(),
            ]);

            /** @var QuoteRequest $request */
            $request = $product->quoteRequests()->create([
                'customer_id' => $customer->id,
                'status' => 'yeni',
                'source' => $source,
                'reference_no' => $this->uniqueReference(),
            ]);

            foreach ($fields as $key => $value) {
                $request->fields()->create([
                    'field_key' => $key,
                    'value' => $value,
                ]);
            }

            $this->providers->openQuotesFor($request);

            return $request;
        });
    }

    private function uniqueReference(): string
    {
        do {
            $reference = strtoupper(Str::random(8));
        } while (QuoteRequest::where('reference_no', $reference)->exists());

        return $reference;
    }
}
