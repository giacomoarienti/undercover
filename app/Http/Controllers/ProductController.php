<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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
            'page' => 'required|integer|min:1',
        ]);

        $materials = collect($validated['materials'])->map(fn ($material_slug) => Material::firstWhere('slug', $material_slug)->id);
        $brands = collect($validated['brands'])->map(fn ($brand_slug) => Brand::firstWhere('slug', $brand_slug)->id);
        $models = collect($validated['models'])->map(fn ($model_slug) => Phone::firstWhere('slug', $model_slug)->id);
        $colors = collect($validated['colors'])->map(fn ($color_slug) => Color::firstWhere('slug', $color_slug)->id);

        $products = ($request->user?->is_vendor ? Product::where('user_id', $request->user->id) : Product::query())
            ->whereLike('name', '%'.$validated['search'].'%') //case-insensitive by default;
            ->whereIn('material_id', $materials)
            ->whereIn('brand_id', $brands)
            ->whereIn('phone_id', $models)
            ->whereIn('color_id', $colors)
            ->paginate(10);

        return view('products.index', $validated)
            ->with('products', $products)
            ->with('user', $request->user());
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
        return view('products.edit')->with('product', null);
    }

    private function carryOut(Request $request)
    {
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

        $product = Product::firstOrCreate([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'phone_id' => $validated['phone_id'],
            ], $validated);
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

        return $product;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);
        $product = $this->carryOut($request);
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
        $product = $this->carryOut($request);
        return redirect()->route('products.show')->with('product', $product);
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
