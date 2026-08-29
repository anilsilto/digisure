<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\QuoteRequest;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('panel.dashboard', [
            'yeniTalep' => QuoteRequest::where('status', 'yeni')->count(),
            'teklifBekleyen' => QuoteRequest::whereHas('quotes', fn ($q) => $q->where('status', 'beklemede'))->count(),
            'buHaftaBiten' => Policy::where('status', 'aktif')
                ->whereBetween('end_date', [now(), now()->addDays(7)])
                ->count(),
        ]);
    }
}
