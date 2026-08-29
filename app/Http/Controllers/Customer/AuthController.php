<?php

namespace App\Http\Controllers\Customer;

use App\Domain\Auth\OtpService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private readonly OtpService $otp)
    {
    }

    public function showStart(): View
    {
        return view('customer.auth.start');
    }

    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
        ]);

        $this->otp->start($data['tc_no'], $data['telefon']);

        return redirect()->route('customer.verify')
            ->with('otp_tc', $data['tc_no'])
            ->with('otp_phone', $data['telefon']);
    }

    public function showVerify(Request $request): View
    {
        return view('customer.auth.verify', [
            'tc_no' => $request->session()->get('otp_tc'),
            'telefon' => $request->session()->get('otp_phone'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
            'kod' => ['required', 'string'],
        ]);

        $customer = $this->otp->verify($data['tc_no'], $data['telefon'], $data['kod']);

        Auth::guard('customer')->login($customer, remember: true);
        $request->session()->regenerate();

        return redirect()->intended('/hesabim');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
