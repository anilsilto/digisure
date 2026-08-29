<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\DataRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DataRequestController extends Controller
{
    public function index(): View
    {
        return view('panel.data-requests.index', [
            'requests' => DataRequest::with('customer')->latest()->paginate(20),
        ]);
    }

    public function markHandled(Request $request, DataRequest $dataRequest): RedirectResponse
    {
        $dataRequest->update([
            'status' => 'tamamlandi',
            'handled_by' => $request->user('panel')->id,
            'handled_at' => now(),
        ]);

        return back()->with('status', 'Talep tamamlandı olarak işaretlendi.');
    }
}
