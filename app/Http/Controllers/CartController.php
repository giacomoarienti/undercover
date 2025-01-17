<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        // check product_id valid
        $product = Product::find($request->get('product_id'));
        $quantity = $request->get('quantity');
        if (!$product) {
            if($request->ajax()) {
                return response()->json(["message" => "Product not found."], 400);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        /** @var User $user */
        $user = Auth::user();

        if($quantity <= 0) {
            $user->cart()->detach($product);
            if($request->ajax()) {
                return response()->json(["message" => "Product removed from cart."]);
            }
            return redirect()->back()->with('message', 'Product removed from cart.');
        }

        $user->cart()->attach($product, ['quantity' => $request->get('quantity')]);

        if($request->ajax()) {
            return response()->json(["message" => "Product added to cart."]);
        }

        return redirect()->back()->with('message', 'Product added to cart.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        // check product_id valid
        $product = Product::find($request->get('product_id'));
        if(!$product) {
            if($request->ajax()) {
                return response()->json(["message" => "Product not found."], 400);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        /** @var User $user */
        $user = Auth::user();
        $user->cart()->detach($product);

        if($request->ajax()) {
            return response()->json(["message" => "Product removed from cart."]);
        }
        return redirect()->back()->with('message', 'Product removed from cart.');
    }
}
