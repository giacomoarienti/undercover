<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Product::class);
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /**
         * Throws AuthorizationException, automatically converted into a 403 HTTP response by Laravel
         */
        Gate::authorize('create', Product::class);
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'material_id' => 'required|integer|exists:materials,id',
            'phone_id' => 'required|integer|exists:phones,id',
            'colors' => 'required|array|min:1',
            'colors.*.id' => 'required|integer|exists:colors,id',
            'colors.*.quantity' => 'required|integer|min:0',
        ]);

        $validated["user_id"] = $request->user->id;

        $product = Product::create($validated);
        $product->updateColors($validated['colors']);

        return redirect()->route('products.show')->with('product', $product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);
        return view('products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return view('products.edit')->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'material_id' => 'required|integer|exists:materials,id',
            'phone_id' => 'required|integer|exists:phones,id',
            'colors' => 'required|array|min:1',
            'colors.*.id' => 'required|integer|exists:colors,id',
            'colors.*.quantity' => 'required|integer|min:0',
        ]);

        $validated["user_id"] = $request->user->id;

        $product->update($validated);
        $product->updateColors($validated['colors']);

        return redirect()->route('products.show')->with('product', $product->first());
    }

    /**
     * Remove the specified resource from storage. (Soft-delete)
     */
    public function destroy(Product $product)
    {
        //TODO observer per notifiche
        Gate::authorize('delete', $product);
        $product->updateColors([]);
        $product->delete();
        return redirect()->route('products.index');
    }
}
