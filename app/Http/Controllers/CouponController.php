<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();
        $coupons = $user->coupons()
            ->orderBy('created_at', 'desc')
            ->paginate(10, page: $request->get('page', 1));

        return view('coupons.index', [
            "coupons" => $coupons,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "code" => "required|string",
            "discount" => "required|integer",
            "starts_at" => "required|date",
            "expires_at" => "required|date|after:starts_at",
        ]);

        /** @var User $user */
        $user = Auth::user();
        if ($user->coupons()->where('code', $request->get('code'))->exists()) {
            return to_route('coupons.index')->withErrors(['code' => 'Coupon already exists']);
        }

        Coupon::create([
            'code' => $request->get('code'),
            'discount' => $request->get('discount') / 100,
            'starts_at' => $request->get('starts_at'),
            'expires_at' => $request->get('expires_at'),
            'user_id' => $user->id,
        ]);

        return to_route('coupons.index')->with('message', 'Coupon created');
    }

    public function edit(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
            "code" => "required|string",
            "discount" => "required|integer",
            "starts_at" => "required|date",
            "expires_at" => "required|date|after:starts_at",
        ]);

        $coupon = $request->user()->coupons()->find($request->get('id'));
        if (!$coupon) {
            return to_route('coupons.index')->withErrors(['id' => 'Coupon not found']);
        }

        $coupon->update([
            'code' => $request->get('code'),
            'discount' => $request->get('discount') / 100,
            'starts_at' => $request->get('starts_at'),
            'expires_at' => $request->get('expires_at'),
        ]);

        return to_route('coupons.index')->with('message', 'Coupon updated');
    }

    public function show(string $code)
    {
        $coupon = Coupon::where('code', $code)->first();
        if(!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        return response()->json(['coupon' => $coupon]);
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
        ]);

        $coupon = $user->coupons()->find($request->get('id'));
        if (!$coupon) {
            return to_route('coupons.index')->withErrors(['id' => 'Coupon not found']);
        }

        $coupon->delete();

        return to_route('coupons.index')->with('message', 'Coupon deleted');
    }
}
