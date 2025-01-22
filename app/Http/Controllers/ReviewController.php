<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\SpecificProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|text|max:500',
            'stars' => 'required|integer|min:0|max:10',
            'product_id' => 'required|integer|exists:products,id',
        ]);
        Gate::authorize('review', SpecificProduct::firstWhere('id', $validated['specific_product_id']));
        $validated['user_id'] = Auth::user()->id;
        $review = Review::create($validated);
        return redirect()->route('products.show', $review->specificProduct->product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|text|max:500',
            'stars' => 'required|integer|min:0|max:10',
            'specific_product_id' => 'required|integer',
        ]);
        Gate::authorize('update', $review);
        $review->update($validated);
        return redirect()->route('products.show', $review->specificProduct->product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        Gate::authorize('delete', $review);
        $review->delete();
        return redirect()->route('products.show', $review->specificProduct->product);
    }
}
