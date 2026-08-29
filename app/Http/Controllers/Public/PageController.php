<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'products' => ProductType::active()->get(),
        ]);
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function kvkk(): View
    {
        return view('public.kvkk');
    }

    public function product(ProductType $product): View
    {
        return view('public.product', ['product' => $product]);
    }
}
