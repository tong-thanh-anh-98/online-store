<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Recond not found',
            ]);
        }

        if (Cart::count() > 0) {
            echo "Product already in cart";
        } else {
            // Cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            echo "Cart is empty now adding a product in cart";
            
        }

        return response()->json([
            'status' => true,
            'message' => $product->title.' added in cart',
        ]);
    }

    public function cart()
    {
        dd(Cart::content());
        // return view('front.cart');
    }

}
