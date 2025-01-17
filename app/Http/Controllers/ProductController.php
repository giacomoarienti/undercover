<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Material;
use App\Models\Phone;
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
        $validated = $request->validate([
            'search' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*' => 'string|exists:materials,slug',
            'brands' => 'nullable|array',
            'brands.*' => 'string|exists:brands,slug',
            'models' => 'nullable|array',
            'models.*' => 'string|exists:models,slug',
            'colors' => 'nullable|array',
            'colors.*' => 'string|exists:colors,slug',
        ]);
        return view('products.index', $validated);
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
            'material_slug' => 'required|string|exists:materials,slug',
            'phone_slug' => 'required|string|exists:phones,slug',
            'colors' => 'required|array|min:1',
            'colors.*.slug' => 'required|string|exists:colors,slug',
            'colors.*.quantity' => 'required|integer|min:0',
        ]);

        $validated["user_id"] = $request->user->id;
        $validated["material_id"] = Material::firstWhere('slug', $validated['material_slug'])->id;
        $validated["phone_id"] = Phone::firstWhere('slug', $validated['phone_slug'])->id;

        $validated["user_id"] = $request->user->id;

        $product = Product::create($validated);
        $product->updateColors(collect($validated['colors'])->map(
            function ($color) {
                return [
                    'color_id' => Color::where(['slug' => $color['slug']])->first()->id,
                    'quantity' => $color['quantity']
                ];
            }
        )->all());

        foreach ($validated['images'] as $image) {
            $product->addMedia($image)->toMediaCollection();
        }

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
            'material_slug' => 'required|string|exists:materials,slug',
            'phone_slug' => 'required|string|exists:phones,slug',
            'colors' => 'required|array|min:1',
            'colors.*.slug' => 'required|string|exists:colors,slug',
            'colors.*.quantity' => 'required|integer|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048', //max 2048 kilobytes
        ]);

        $validated["user_id"] = $request->user->id;
        $validated["material_id"] = Material::firstWhere('slug', $validated['material_slug'])->id;
        $validated["phone_id"] = Phone::firstWhere('slug', $validated['phone_slug'])->id;

        $product->update($validated);
        $product->updateColors(collect($validated['colors'])->map(
            function ($color) {
                return [
                    'color_id' => Color::where(['slug' => $color['slug']])->first()->id,
                    'quantity' => $color['quantity']
                ];
            }
        )->all());

        $product->clearMediaCollection();
        foreach ($validated['images'] as $image) {
            $product->addMedia($image)->toMediaCollection();
        }

        return redirect()->route('products.show')->with('product', $product->first());
    }

    /**
     * Remove the specified resource from storage. (Soft-delete)
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index');
    }
}
