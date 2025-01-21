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

        $filters = $request->validate([
            'search' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*' => 'string|exists:materials,slug',
            'brands' => 'nullable|array',
            'brands.*' => 'string|exists:brands,slug',
            'phone' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'string|exists:colors,slug',
            'page' => 'nullable|integer|min:1',
        ]);

        $user = Auth::user();

        //we translate from slugs to ids
        $materials = isset($filters['materials'])
            ? collect($filters['materials'])->map(fn ($material_slug) => Material::firstWhere('slug', $material_slug)->id)
            : [];

        $brands = isset($filters['brands'])
            ? collect($filters['brands'])->map(fn ($brand_slug) => Brand::firstWhere('slug', $brand_slug)->id)
            : [];

        $phones = isset($filters['phone'])
            ? Phone::whereLike('name', '%'.$filters['phone'].'%')->get('id')->all()
            : [];

        $colors = isset($filters['colors'])
            ? collect($filters['colors'])->map(fn ($color_slug) => Color::firstWhere('slug', $color_slug)->id)
            : [];

        $products = $user?->is_vendor ? Product::where('user_id', $user->id) : Product::query();

        if (!empty($filters['search'])) {
            $products = $products->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($materials)) {
            $products = $products->whereIn('material_id', $materials);
        }

        if (!empty($brands)) {
            $products = $products->whereHas('phone', function ($query) use ($brands) {
                $query->whereIn('brand_id', $brands);
            });
        }

        if (!empty($phones)) {
            $products = $products->whereIn('phone_id', $phones);
        }

        if (!empty($colors)) {
            $products = $products->whereHas('specificProducts', function ($query) use ($colors) {
                $query->whereIn('color_id', $colors);
            });
        }

        $products = $products->paginate(12, page : $filters['page'] ?? 1)->withQueryString();

        return view('products.index', compact('filters', 'products', 'user'));
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
        $phone = $brand->addPhone($validated['phone']);

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
