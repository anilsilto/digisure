<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DataRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DataRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:indir,sil'],
        ]);

        DataRequest::create([
            'customer_id' => $request->user('customer')->id,
            'type' => $data['type'],
        ]);

        return back()->with('status', 'Talebiniz alındı, KVKK kapsamında en kısa sürede işleme alınacaktır.');
    }
}
