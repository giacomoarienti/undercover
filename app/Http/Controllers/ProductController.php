<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Material;
use App\Models\Phone;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
            'page' => 'nullable|integer|min:1',
        ]);

        //we translate from slugs to ids
        $materials = isset($validated['materials'])
            ? collect($validated['materials'])->map(fn ($material_slug) => Material::firstWhere('slug', $material_slug)->id)
            : [];

        $brands = isset($validated['brands'])
            ? collect($validated['brands'])->map(fn ($brand_slug) => Brand::firstWhere('slug', $brand_slug)->id)
            : [];

        $models = isset($validated['models'])
            ? collect($validated['models'])->map(fn ($model_slug) => Phone::firstWhere('slug', $model_slug)->id)
            : [];

        $colors = isset($validated['colors'])
            ? collect($validated['colors'])->map(fn ($color_slug) => Color::firstWhere('slug', $color_slug)->id)
            : [];

        $products = $request->user?->is_vendor ? Product::where('user_id', $request->user->id) : Product::query();

        if (!empty($validated['search'])) {
            $products = $products->where('name', 'like', '%' . $validated['search'] . '%');
        }

        if (!empty($materials)) {
            $products = $products->whereIn('material_id', $materials);
        }

        if (!empty($brands)) {
            $products = $products->whereIn('brand_id', $brands);
        }

        if (!empty($models)) {
            $products = $products->whereIn('phone_id', $models);
        }

        if (!empty($colors)) {
            $products = $products->whereIn('color_id', $colors);
        }

        $products = $products->paginate(10, $page=$validated['page'] ?? 1);

        return view('products.index')
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
            'description' => 'required|string|max:511',
            'price' => 'required|numeric|min:0',
            'material_id' => 'required|integer|exists:materials,id',
            'phone' => 'required|string',
            'brand' => 'required|string',
            'colors' => 'required|array',
            'colors.*.checkbox' => 'nullable|boolean',
            'colors.*.quantity' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $key = str_replace(['colors.', '.quantity'], '', $attribute);
                    $checkbox = request()->input("colors.$key.checkbox");
                    if ($checkbox && is_null($value)) {
                        $fail('The quantity field is required when the checkbox is selected.');
                    }
                }
            ],
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        error_log("C");

        $user = Auth::user();

        $brand = Brand::firstWhere(['name' => $validated['brand']]);
        if(!$brand) {
            Gate::authorize('create', Brand::class);
            $brand = Brand::create(['name' => $validated['brand']]);
        }
        $phone = $brand->addPhone($validated['name']);

        $product = Product::firstOrCreate([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'phone_id' => $phone->id,
            ], $validated);

        $product->updateColors(collect(array_keys($validated['colors']))->map(
            function ($color) use ($validated) {
                return [
                    'color_id' => $color,
                    'quantity' => $validated['colors'][$color]['quantity'],
                ];
            }
        )->all());

        foreach ($validated['images'] as $image) {
            error_log("Immagine");
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

        error_log(json_encode($request->all()));

        $product = $this->carryOut($request);

        return redirect()->route('products.show', ['product' => $product->slug]);
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
        return redirect()->route('products.show', ['product' => $product->slug]);
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
