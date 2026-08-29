<?php

namespace App\Http\Controllers\Customer;

use App\Domain\Quote\QuoteRequestWorkflow;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $requests = QuoteRequest::where('customer_id', $request->user('customer')->id)
            ->with('productType')
            ->withCount(['quotes as verildi_count' => fn ($q) => $q->where('status', 'verildi')])
            ->latest()
            ->get();

        return view('customer.quotes.index', ['requests' => $requests]);
    }

    public function show(Request $request, QuoteRequest $quoteRequest): View
    {
        $this->authorizeOwnership($request, $quoteRequest);

        $quotes = $quoteRequest->quotes()
            ->where('status', 'verildi')
            ->orderBy('premium')
            ->get();

        return view('customer.quotes.show', [
            'quoteRequest' => $quoteRequest->load('productType'),
            'quotes' => $quotes,
            'insurerLabels' => config('digisure.insurer_labels'),
        ]);
    }

    public function accept(Request $request, QuoteRequest $quoteRequest, Quote $quote): RedirectResponse
    {
        $this->authorizeOwnership($request, $quoteRequest);
        abort_unless($quote->quote_request_id === $quoteRequest->id, 404);

        QuoteRequestWorkflow::accept($quoteRequest, $quote);

        return redirect()
            ->route('customer.quotes.show', $quoteRequest)
            ->with('status', 'Seçiminiz iletildi. Acentemiz poliçeleştirme için sizinle iletişime geçecek.');
    }

    private function authorizeOwnership(Request $request, QuoteRequest $quoteRequest): void
    {
        abort_unless($quoteRequest->customer_id === $request->user('customer')->id, 404);
    }
}
