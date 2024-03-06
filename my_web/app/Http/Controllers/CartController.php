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
            // Product found in cart
            // Check if this product already in the cart
            // Return as message that product already added in your cart
            // if product not found in the cart, then add product in cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;
            
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }

                if ($productAlreadyExist == false) {
                    Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                    $status = true;
                    $message = $product->title.' added in cart';
                } else {
                    $status = false;
                    $message = $product->title.' already added in cart';
                }
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = $product->title.' added in cart';
        }

        return response()->json([
            'status' =>  $status,
            'message' => $message,
        ]);
    }

    public function cart()
    {
        $product = Product::with('product_images')->first();
        $cartContent = Cart::content();

        $data = [];
        $data['cartContent'] = $cartContent;
        $data['product'] = $product;

        return view('front.cart', $data);
    }

}
