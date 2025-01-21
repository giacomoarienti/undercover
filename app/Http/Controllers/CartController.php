<?php

namespace App\Http\Controllers;

use App\Models\SpecificProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $cart = $user->cart;

        if($request->ajax()) {
            return response()->json(["cart" => $cart]);
        }

        return view(
            'user.cart',
            ["cart" => $cart]
        );
    }

    public function add(Request $request)
    {
        $request->validate([
            'specific_product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        // check product_id valid
        $product = SpecificProduct::find($request->get('specific_product_id'));
        $quantity = $request->get('quantity');
        if (!$product) {
            if($request->ajax()) {
                return response()->json(["message" => "Product not found."], 400);
            }
            return to_route('cart')->with('error', 'Product not found.');
        }

        /** @var User $user */
        $user = Auth::user();

        if($quantity <= 0) {
            $user->removeFromCart($product);

            if($request->ajax()) {
                return response()->json(["message" => "Product removed from cart."]);
            }
            return to_route('cart')->with('message', 'Product removed from cart.');
        }

        if(!$user->addToCart($product, $quantity)) {
            if($request->ajax()) {
                return response()->json(["message" => "The specified quantity is not available."], 400);
            }
            return to_route('cart')->with('error', 'The specified quantity is not available.');
        }

        if($request->ajax()) {
            return response()->json(["message" => "Product added to cart."]);
        }

        return to_route('cart')->with('message', 'Product added to cart.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'specific_product_id' => 'required|integer',
        ]);

        // check product_id valid
        $product = SpecificProduct::find($request->get('specific_product_id'));
        if(!$product) {
            if($request->ajax()) {
                return response()->json(["message" => "Product not found."], 400);
            }
            return to_route('cart')->with('error', 'Product not found.');
        }

        /** @var User $user */
        $user = Auth::user();
        $user->removeFromCart($product);

        if($request->ajax()) {
            return response()->json(["message" => "Product removed from cart."]);
        }
        return to_route('cart')->with('message', 'Product removed from cart.');
    }
}
