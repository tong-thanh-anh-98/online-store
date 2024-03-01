<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $products = Product::orderBy('id','ASC')->where('status',1)->get();

        $data = [];
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;

        return view('front.store',$data);
    }
}
