<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Quote\QuoteRequestService;
use App\Domain\Risk\AssetDeriver;
use App\Domain\Risk\RiskAnalyzer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAsset;
use App\Models\ProductType;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct(
        private readonly AssetDeriver $deriver,
        private readonly RiskAnalyzer $analyzer,
    ) {}

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $customers = Customer::query()
            ->withCount(['policies as aktif_police_count' => fn ($query) => $query->where('status', 'aktif')])
            ->when($q !== '', fn ($query) => $query->where(
                fn ($sub) => $sub->where('first_name', 'like', "%{$q}%")->orWhere('last_name', 'like', "%{$q}%")
            ))
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('panel.customers.index', ['customers' => $customers, 'q' => $q]);
    }

    public function show(Customer $customer): View
    {
        $this->deriver->sync($customer);

        return view('panel.customers.show', [
            'customer' => $customer,
            'report' => $this->analyzer->forCustomer($customer),
            'assets' => $customer->assets()->active()
                ->whereIn('type', array_keys(CustomerAsset::TYPES))->get(),
            'assetTypes' => CustomerAsset::TYPES,
        ]);
    }

    public function storeAsset(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(CustomerAsset::TYPES))],
            'label' => ['required', 'string', 'max:80'],
        ]);

        $customer->assets()->create([
            'type' => $data['type'],
            'label' => trim($data['label']),
            'source' => 'acente',
            'status' => 'aktif',
            'created_by_user_id' => $request->user('panel')->id,
        ]);

        ActivityLogger::log('risk.varlik_eklendi', $customer, $data);

        return back()->with('status', 'Varlık eklendi.');
    }

    public function removeAsset(Customer $customer, CustomerAsset $asset): RedirectResponse
    {
        abort_unless($asset->customer_id === $customer->id, 404);

        $asset->update(['status' => 'pasif']);

        return back()->with('status', 'Varlık kaldırıldı.');
    }

    public function createLead(Request $request, Customer $customer): RedirectResponse
    {
        $activeKeys = ProductType::active()->pluck('key')->all();

        $data = $request->validate([
            'product_key' => ['required', Rule::in($activeKeys)],
        ]);

        $product = ProductType::where('key', $data['product_key'])->firstOrFail();

        app(QuoteRequestService::class)->create(
            product: $product,
            fields: [],
            customerAttrs: [
                'tc_no' => $customer->tc_no,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
            source: 'panel',
            kvkk: true,
        );

        ActivityLogger::log('risk.acente_teklif_talebi', $customer, ['product' => $product->key]);

        return back()->with('status', 'Teklif talebi oluşturuldu; Teklifler ekranına düştü.');
    }
}
