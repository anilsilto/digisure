<?php

namespace App\Http\Controllers\Public;

use App\Domain\Quote\QuoteRequestService;
use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Support\DynamicForm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteRequestController extends Controller
{
    public function form(Request $request): View
    {
        $products = ProductType::active()->get();
        $selected = $products->firstWhere('key', $request->query('urun')) ?? $products->first();

        return view('public.quote.form', [
            'products' => $products,
            'selected' => $selected,
            'labels' => $selected ? DynamicForm::labels($selected) : [],
        ]);
    }

    public function store(Request $request, QuoteRequestService $service): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return redirect()->route('teklif.form');
        }

        $product = ProductType::active()->where('key', $request->input('urun'))->firstOrFail();

        $validated = $request->validate(array_merge(DynamicForm::rules($product), [
            'urun' => ['required', 'string'],
            'ad' => ['required', 'string', 'max:255'],
            'soyad' => ['required', 'string', 'max:255'],
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
            'eposta' => ['nullable', 'email', 'max:255'],
            'kvkk' => ['accepted'],
        ]));

        $quoteRequest = $service->create(
            product: $product,
            fields: $validated['fields'] ?? [],
            customerAttrs: [
                'tc_no' => $validated['tc_no'],
                'first_name' => $validated['ad'],
                'last_name' => $validated['soyad'],
                'phone' => $validated['telefon'],
                'email' => $validated['eposta'] ?? null,
            ],
            source: 'site',
            kvkk: $request->boolean('kvkk'),
        );

        return redirect()
            ->route('teklif.received')
            ->with('reference_no', $quoteRequest->reference_no);
    }

    public function received(): View
    {
        return view('public.quote.received', [
            'reference_no' => session('reference_no'),
        ]);
    }
}
