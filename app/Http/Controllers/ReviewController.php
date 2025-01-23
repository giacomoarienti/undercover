<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            'body' => 'required|string|max:500',
            'stars' => 'required|integer|min:0|max:10',
            'product_id' => 'required|integer|exists:products,id',
        ]);
        Gate::authorize('review', Product::firstWhere('id', $validated['product_id']));
        $review = Review::create($validated + ['user_id' => Auth::id()]);

        return redirect()->route('products.show', $review->product->slug);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string|max:500',
            'stars' => 'required|integer|min:0|max:10',
            'product_id' => 'required|integer|exists:products,id',
        ]);
        Gate::authorize('update', $review);
        $review->update($validated);
        return redirect()->route('products.show', $review->product->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        Gate::authorize('delete', $review);
        $review->delete();
        return redirect()->route('products.show', $review->product->slug);
    }
}
