<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    //TODO: authorization

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->resolveView('products.index');
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
        return $this->resolveView('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);
        //TODO
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);
        return $this->resolveView('products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return $this->resolveView('products.edit')->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);
        //TODO
    }

    /**
     * Remove the specified resource from storage. (Soft-delete)
     */
    public function destroy(Product $product)
    {
        //TODO observer per notifiche
        Gate::authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index');
    }
}
