<?php

namespace App\Http\Controllers\Public;

use App\Domain\Calc\Fuel;
use App\Domain\Calc\KaskoValue;
use App\Domain\Calc\Mtv;
use App\Domain\Calc\Otv;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(): View
    {
        return view('public.calc.index');
    }

    public function mtv(Request $request): View
    {
        $result = null;

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'motor_cc' => ['required', 'integer', 'min:1'],
                'arac_yasi' => ['required', 'integer', 'min:0'],
            ]);

            try {
                $result = Mtv::calc($data['motor_cc'], $data['arac_yasi']);
            } catch (\InvalidArgumentException $e) {
                $result = $e->getMessage();
            }
        }

        return view('public.calc.mtv', ['result' => $result]);
    }

    public function otv(Request $request): View
    {
        $result = null;

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'motor_cc' => ['required', 'integer', 'min:1'],
                'matrah' => ['required', 'numeric', 'min:0'],
            ]);

            try {
                $result = Otv::calc($data['motor_cc'], (float) $data['matrah']);
            } catch (\InvalidArgumentException $e) {
                $result = $e->getMessage();
            }
        }

        return view('public.calc.otv', ['result' => $result]);
    }

    public function fuel(Request $request): View
    {
        $result = null;

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'mesafe' => ['required', 'numeric', 'min:0'],
                'tuketim' => ['nullable', 'numeric', 'min:0'],
                'fiyat' => ['nullable', 'numeric', 'min:0'],
            ]);

            $result = Fuel::calc(
                (float) $data['mesafe'],
                isset($data['tuketim']) ? (float) $data['tuketim'] : null,
                isset($data['fiyat']) ? (float) $data['fiyat'] : null,
            );
        }

        return view('public.calc.fuel', ['result' => $result]);
    }

    public function kasko(Request $request): View
    {
        $result = null;

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'referans_deger' => ['required', 'numeric', 'min:0'],
                'oran' => ['nullable', 'numeric', 'min:0', 'max:100'],
            ]);

            $result = KaskoValue::estimate(
                (float) $data['referans_deger'],
                isset($data['oran']) ? (float) $data['oran'] : (float) config('digisure.rates.kasko.default_rate_pct'),
            );
        }

        return view('public.calc.kasko', ['result' => $result]);
    }
}
