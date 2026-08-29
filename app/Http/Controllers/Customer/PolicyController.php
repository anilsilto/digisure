<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PolicyController extends Controller
{
    public function index(Request $request): View
    {
        $policies = Policy::where('customer_id', $request->user('customer')->id)
            ->with('productType')
            ->orderByDesc('end_date')
            ->get();

        return view('customer.policies.index', [
            'policies' => $policies,
            'insurerLabels' => config('digisure.insurer_labels'),
        ]);
    }

    public function download(Request $request, Policy $policy): StreamedResponse
    {
        abort_unless($policy->customer_id === $request->user('customer')->id, 404);
        abort_unless($policy->file_path && Storage::disk('policies')->exists($policy->file_path), 404);

        return Storage::disk('policies')->download($policy->file_path, "police-{$policy->policy_no}.pdf");
    }
}
