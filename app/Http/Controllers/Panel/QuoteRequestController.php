<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Quote\QuoteRequestWorkflow;
use App\Domain\Quote\QuoteStatus;
use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = QuoteRequest::query()
            ->with(['customer', 'productType', 'assignedUser'])
            ->withCount(['quotes as bekleyen_count' => fn ($q) => $q->where('status', 'beklemede')])
            ->latest();

        if ($durum = $request->query('durum')) {
            if ($durum === 'teklif_bekleyen') {
                $query->whereHas('quotes', fn ($q) => $q->where('status', 'beklemede'));
            } else {
                $query->where('status', $durum);
            }
        }

        if ($urun = $request->query('urun')) {
            $query->whereHas('productType', fn ($q) => $q->where('key', $urun));
        }

        if ($personel = $request->query('personel')) {
            $query->where('assigned_user_id', $personel);
        }

        if ($term = $request->query('q')) {
            $query->where(fn ($q) => $q
                ->where('reference_no', 'like', "%{$term}%")
                ->orWhereHas('customer', fn ($c) => $c
                    ->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")));
        }

        return view('panel.quotes.index', [
            'requests' => $query->paginate(20)->withQueryString(),
            'statuses' => QuoteStatus::options(),
            'products' => ProductType::active()->get(),
            'staff' => User::where('is_active', true)->orderBy('name')->get(),
            'filters' => $request->only(['durum', 'urun', 'personel', 'q']),
        ]);
    }

    public function show(QuoteRequest $quoteRequest): View
    {
        $quoteRequest->load(['customer', 'productType', 'fields', 'quotes' => fn ($q) => $q->orderBy('insurer'), 'assignedUser']);

        return view('panel.quotes.show', [
            'quoteRequest' => $quoteRequest,
            'labels' => \App\Support\DynamicForm::labels($quoteRequest->productType),
            'insurerLabels' => config('digisure.insurer_labels'),
            'staff' => User::where('is_active', true)->orderBy('name')->get(),
            'canReveal' => (bool) $this->currentUser()?->isAdmin(),
        ]);
    }

    public function assign(Request $request, QuoteRequest $quoteRequest): RedirectResponse
    {
        $data = $request->validate([
            'assigned_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $quoteRequest->update(['assigned_user_id' => $data['assigned_user_id'] ?? null]);

        return back()->with('status', 'Talep atandı.');
    }

    public function markReady(QuoteRequest $quoteRequest): RedirectResponse
    {
        if (! $quoteRequest->quotes()->where('status', 'verildi')->exists()) {
            throw ValidationException::withMessages([
                'hazir' => 'En az bir şirketten teklif girilmeden hazır işaretlenemez.',
            ]);
        }

        QuoteRequestWorkflow::markQuotesReady($quoteRequest);

        return back()->with('status', 'Teklifler hazır olarak işaretlendi, müşteriye bilgi verildi.');
    }

    private function currentUser(): ?User
    {
        return auth('panel')->user();
    }
}
