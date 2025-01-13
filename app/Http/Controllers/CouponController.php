<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('seller.coupons', [
            'coupons' => Auth::user()->coupons()
        ]);
    }

    public function show($id): View
    {
        return view('seller.coupon', [
            'coupon' => Auth::user()->coupons()->find($id)
        ]);
    }

    public function store(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            "code" => "required|string",
            "discount" => "required|integer",
            "starts_at" => "required|date",
            "expires_at" => "required|date|after:starts_at",
        ]);

        /** @var User $user */
        $user = Auth::user();
        if($user->coupons()->where('code', $request->get('code'))->exists()) {
            return to_route('coupons')->withErrors(['code' => 'Coupon already exists']);
        }

        $user->coupons()->create($request->data());

        return to_route('coupons');
    }

    public function destroy($id): RedirectResponse
    {
        /** @var Coupon $coupon */
        $coupon = Auth::user()->coupons()->find($id);
        if(!$coupon) {
            return to_route('coupons')->withErrors(['id' => 'Coupon not found']);
        }

        $coupon->delete();

        return to_route('coupons');
    }
}
