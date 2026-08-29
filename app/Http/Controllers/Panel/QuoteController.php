<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $data = $request->validate([
            'premium' => ['required', 'numeric', 'min:0'],
            'policy_period_months' => ['nullable', 'integer', 'min:1', 'max:36'],
            'coverage_summary' => ['nullable', 'string', 'max:5000'],
            'insurer_quote_no' => ['nullable', 'string', 'max:255'],
            'valid_until' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        $coverage = collect(preg_split('/\r\n|\r|\n/', (string) ($data['coverage_summary'] ?? '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        $quote->update([
            'premium' => $data['premium'],
            'policy_period_months' => $data['policy_period_months'] ?? null,
            'coverage_summary' => $coverage ?: null,
            'insurer_quote_no' => $data['insurer_quote_no'] ?? null,
            'valid_until' => $data['valid_until'] ?? null,
            'status' => 'verildi',
            'entered_by' => $request->user('panel')->id,
            'file_path' => $request->hasFile('file')
                ? $request->file('file')->store('', 'quotes')
                : $quote->file_path,
        ]);

        ActivityLogger::log('kotasyon.girildi', $quote, ['insurer' => $quote->insurer, 'premium' => $quote->premium]);

        return back()->with('status', config("digisure.insurer_labels.{$quote->insurer}", $quote->insurer).' teklifi kaydedildi.');
    }
}
