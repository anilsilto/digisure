<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('customer.profile.edit', ['customer' => $request->user('customer')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = $request->user('customer');
        $customer->email = $data['email'] ?? null;
        $customer->address = $data['address'] ?? null;
        $customer->marketing_consent_at = $request->boolean('marketing_consent')
            ? ($customer->marketing_consent_at ?? now())
            : null;
        $customer->save();

        return back()->with('status', 'Bilgileriniz güncellendi.');
    }
}
