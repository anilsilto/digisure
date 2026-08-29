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
    public function __construct(private readonly OtpService $otp) {}

    public function showStart(): View
    {
        return view('customer.auth.start');
    }

    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
        ], [], [
            'tc_no' => 'TC Kimlik No',
            'telefon' => 'Telefon',
        ]);

        $this->otp->start($data['tc_no'], $data['telefon']);

        // Kalıcı sakla (flash değil) — kod yanlış girilip sayfa yeniden yüklense de kaybolmasın.
        $request->session()->put('otp_tc', $data['tc_no']);
        $request->session()->put('otp_phone', $data['telefon']);

        return redirect()->route('customer.verify');
    }

    public function showVerify(Request $request): RedirectResponse|View
    {
        if (! $request->session()->has('otp_tc')) {
            return redirect()->route('customer.login');
        }

        return view('customer.auth.verify', [
            'tc_no' => $request->session()->get('otp_tc'),
            'telefon' => $request->session()->get('otp_phone'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        // TC/telefon oturumdan gelir; formdan yalnızca kod alınır.
        $request->merge([
            'tc_no' => $request->session()->get('otp_tc'),
            'telefon' => $request->session()->get('otp_phone'),
        ]);

        $data = $request->validate([
            'tc_no' => ['required', 'string', 'size:11'],
            'telefon' => ['required', 'string', 'max:32'],
            'kod' => ['required', 'string'],
        ], [], ['kod' => 'Kod']);

        $customer = $this->otp->verify($data['tc_no'], $data['telefon'], $data['kod']);

        Auth::guard('customer')->login($customer, remember: true);
        $request->session()->regenerate();
        $request->session()->forget(['otp_tc', 'otp_phone']);

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
