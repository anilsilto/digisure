<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Policy\PolicyService;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function __construct(private readonly PolicyService $policies)
    {
    }

    public function index(Request $request): View
    {
        $query = Policy::query()->with(['customer', 'productType'])->latest('end_date');

        if ($durum = $request->query('durum')) {
            $query->where('status', $durum);
        }
        if ($insurer = $request->query('insurer')) {
            $query->where('insurer', $insurer);
        }

        return view('panel.policies.index', [
            'policies' => $query->paginate(20)->withQueryString(),
            'upcoming' => Policy::where('status', 'aktif')
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->with('customer')
                ->orderBy('end_date')
                ->get(),
            'insurerLabels' => config('digisure.insurer_labels'),
            'filters' => $request->only(['durum', 'insurer']),
        ]);
    }

    public function create(): View
    {
        return view('panel.policies.create', [
            'products' => ProductType::active()->get(),
            'insurerLabels' => config('digisure.insurer_labels'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'urun' => ['required', 'exists:product_types,key'],
            'insurer' => ['required', 'string', 'in:' . implode(',', array_keys(config('digisure.insurer_labels')))],
            'policy_no' => ['required', 'string', 'max:255'],
            'premium' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'ad' => ['required', 'string', 'max:255'],
            'soyad' => ['required', 'string', 'max:255'],
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
            'eposta' => ['nullable', 'email', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('', 'policies');
        }

        $policy = $this->policies->createManual($data);

        return redirect()->route('panel.policies.show', $policy)->with('status', 'Poliçe eklendi.');
    }

    public function show(Policy $policy): View
    {
        return view('panel.policies.show', [
            'policy' => $policy->load(['customer', 'productType', 'reminders']),
            'insurerLabels' => config('digisure.insurer_labels'),
        ]);
    }

    public function fromQuote(Request $request, QuoteRequest $quoteRequest): RedirectResponse
    {
        abort_unless(
            $quoteRequest->status === 'kabul',
            422,
            'Sadece kabul edilmiş talepler poliçeleştirilebilir.',
        );

        $data = $request->validate([
            'policy_no' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('', 'policies');
        }

        $policy = $this->policies->fromAcceptedQuote($quoteRequest, $data);

        return redirect()->route('panel.policies.show', $policy)->with('status', 'Poliçe oluşturuldu.');
    }
}
