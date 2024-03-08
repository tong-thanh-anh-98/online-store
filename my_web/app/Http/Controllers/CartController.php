<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the status and message.
     */
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
                    $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
                    session()->flash('success', $message);
                } else {
                    $status = false;
                    $message = $product->title.' already added in cart.';
                }
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' =>  $status,
            'message' => $message,
        ]);
    }

    /**
     * Display the cart page showing the contents of the cart.
     *
     * @return \Illuminate\Contracts\View\View The view for the cart page.
     */
    public function cart()
    {
        $cartContent = Cart::content();
        $data = [];
        $data['cartContent'] = $cartContent;

        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        /* Check qty available in stock */
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $status = true;
                $message = 'Cart updated successfully.';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = 'Requested qty('.$qty.') not available in stock';
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $status = true;
            $message = 'Cart updated successfully.';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' =>  $status,
            'message' => $message,
        ]);
    }

    /**
     * Delete an item from the cart.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the status and message.
     */
    public function deleteItem(Request $request)
    {
        $errorMessage = 'Item not found in cart.';
        $message = 'Item removed from cart successfully.';
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            session()->flash('error', $errorMessage);

            return response()->json([
                'status' =>  false,
                'message' => $errorMessage,
            ]);
        }

        Cart::remove($request->rowId);

        session()->flash('success', $message);

        return response()->json([
            'status' =>  true,
            'message' => $message,
        ]);
    }

    public function checkout()
    {
        /* Cart is empty redirect to cart page */
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        /* User is not logged in then redirect to login page */
        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        session()->forget('url.intended');

        return view('front.checkout');
    }
}
