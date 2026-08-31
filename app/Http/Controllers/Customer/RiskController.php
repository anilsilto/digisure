<?php

namespace App\Http\Controllers\Customer;

use App\Domain\Quote\QuoteRequestService;
use App\Domain\Risk\AssetDeriver;
use App\Domain\Risk\RiskAnalyzer;
use App\Http\Controllers\Controller;
use App\Models\CustomerAsset;
use App\Models\ProductType;
use App\Models\RiskEvent;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiskController extends Controller
{
    public function __construct(
        private readonly AssetDeriver $deriver,
        private readonly RiskAnalyzer $analyzer,
    ) {}

    public function show(Request $request): View
    {
        $customer = $request->user('customer');
        $this->deriver->sync($customer);
        $report = $this->analyzer->forCustomer($customer);

        $alreadyLogged = RiskEvent::where('customer_id', $customer->id)
            ->where('type', 'panel_goruntulendi')
            ->whereDate('created_at', today())
            ->exists();

        if (! $alreadyLogged) {
            RiskEvent::create([
                'customer_id' => $customer->id,
                'type' => 'panel_goruntulendi',
                'meta' => ['risk_pct' => $report->riskPct],
            ]);
        }

        return view('customer.risk.show', [
            'report' => $report,
            'declaredAssets' => $customer->assets()->active()
                ->whereIn('type', array_keys(CustomerAsset::TYPES))->get(),
            'assetTypes' => CustomerAsset::TYPES,
        ]);
    }

    public function storeAsset(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(CustomerAsset::TYPES))],
            'label' => ['required', 'string', 'max:80'],
        ]);

        $request->user('customer')->assets()->create([
            'type' => $data['type'],
            'label' => trim($data['label']),
            'source' => 'musteri',
            'status' => 'aktif',
        ]);

        return back()->with('status', 'Varlık eklendi.');
    }

    public function removeAsset(Request $request, CustomerAsset $asset): RedirectResponse
    {
        abort_unless($asset->customer_id === $request->user('customer')->id, 403);

        $asset->update(['status' => 'pasif']);

        return back()->with('status', 'Varlık kaldırıldı.');
    }

    public function createLead(Request $request): RedirectResponse
    {
        $activeKeys = ProductType::active()->pluck('key')->all();

        $data = $request->validate([
            'product_key' => ['required', Rule::in($activeKeys)],
            'asset_id' => ['nullable', 'integer'],
        ]);

        $customer = $request->user('customer');
        $product = ProductType::where('key', $data['product_key'])->firstOrFail();

        $asset = ! empty($data['asset_id'])
            ? $customer->assets()->find($data['asset_id'])
            : null;

        $quoteRequest = app(QuoteRequestService::class)->create(
            product: $product,
            fields: $this->assetFields($asset),
            customerAttrs: [
                'tc_no' => $customer->tc_no,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
            source: 'risk_paneli',
            kvkk: true,
        );

        RiskEvent::create([
            'customer_id' => $customer->id,
            'customer_asset_id' => $asset?->id,
            'quote_request_id' => $quoteRequest->id,
            'type' => 'talep_olusturuldu',
            'product_key' => $product->key,
        ]);

        ActivityLogger::log('risk.teklif_talebi', $quoteRequest, ['product' => $product->key, 'kaynak' => 'risk_paneli']);

        return redirect()->route('teklif.received')->with('reference_no', $quoteRequest->reference_no);
    }

    /**
     * @return array<string, string|null>
     */
    private function assetFields(?CustomerAsset $asset): array
    {
        $meta = $asset?->meta ?? [];

        return array_filter([
            'plaka' => $meta['plaka'] ?? null,
            'il' => $meta['il'] ?? null,
            'ilce' => $meta['ilce'] ?? null,
        ], fn ($value) => filled($value));
    }
}
