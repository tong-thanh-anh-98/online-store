<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Display the home page with featured and latest products.
     *
     * @return \Illuminate\Contracts\View\View The view for the home page.
     */
    public function index()
    {
        $isFeatured = Product::where('is_featured','Yes')
                    ->orderBy('id','DESC')
                    ->take(8)->where('status',1)
                    ->get();
        $latestProducts = Product::orderBy('id','DESC')
                        ->where('status',1)
                        ->take(8)
                        ->get();

        $data = [];
        $data['isFeatured'] = $isFeatured;
        $data['latestProducts'] = $latestProducts;

        return view('front.home', $data);
    }
}
