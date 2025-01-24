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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //even non-auth users can make this request

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

        $user = Auth::hasUser() ? Auth::user() : null;

        //we translate from slugs to ids
        $materials = isset($filters['materials'])
            ? collect($filters['materials'])->map(fn ($material_slug) => Material::firstWhere('slug', $material_slug)->id)
            : [];

        $brands = isset($filters['brands'])
            ? collect($filters['brands'])->map(fn ($brand_slug) => Brand::firstWhere('slug', $brand_slug)->id)
            : [];

        $phones = isset($filters['phone'])
            ? Phone::whereLike('name', '%'.$filters['phone'].'%')->get('id')->map(fn ($phone) => $phone['id'])->all()
            : null;

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

        if ($phones != null) {
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

    private function carryOut($validated)
    {
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

        foreach ($validated['delete_images'] ?? [] as $id) {
            Media::find($id)->delete();
        }

        foreach ($validated['images'] ?? [] as $image) {
            Log::info("Immagine");
            $product->addMedia($image)->toMediaCollection('images');
        }

        return $product;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:511',
            'price' => 'required|numeric|min:0.01',
            'material_id' => 'required|integer|exists:materials,id',
            'phone' => 'required|string',
            'brand' => 'required|string',
            'colors' => 'required|array',
            'colors.*.selected' => 'nullable',
            'colors.*.quantity' => 'exclude_if:colors.*.selected,false|required|integer|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'name.required' => 'The product name is required.',
                'description.required' => 'A description is required.',
                'price.required' => 'The price is required.',
                'price.min' => 'The price must be greater than 0.',
                'material_id.required' => 'Please select a material.',
                'material_id.exists' => 'The selected material does not exist.',
                'phone.required' => 'A phone model is required.',
                'brand.required' => 'A brand name is required.',
                'colors.required' => 'At least one color must be selected.',
                'colors.*.quantity.required' => 'The quantity is required when a color is selected.',
                'colors.*.quantity.min' => 'The quantity of any selected color must be at least 1.',
                'images.required' => 'Please upload at least one image.',
                'images.*.image' => 'Each file must be an image.',
                'images.*.mimes' => 'Images must be in one of the following formats: jpeg, png, jpg, gif, svg.',
                'images.*.max' => 'Each image must not exceed 2 MB.',
            ]
        );

        $product = $this->carryOut($validated);

        return redirect()->route('products.show', $product->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);

        $user = Auth::user();
        $reviews = $product->reviews()->whereNot('user_id', $user->id)->paginate(5);
        $userReview = $user ? $product->reviews()->where('user_id', $user->id)->first() : null;

        return view('products.show', compact('product', 'reviews', 'userReview', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:511',
            'price' => 'required|numeric|min:0.01',
            'material_id' => 'required|integer|exists:materials,id',
            'phone' => 'required|string',
            'brand' => 'required|string',
            'colors' => 'required|array',
            'colors.*.selected' => 'nullable',
            'colors.*.quantity' => 'exclude_if:colors.*.selected,false|required|integer|min:1',
            'images' => 'nullable|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer|exists:media,id',
        ],
            [
                'name.required' => 'The product name is required.',
                'description.required' => 'A description is required.',
                'price.required' => 'The price is required.',
                'price.min' => 'The price must be greater than 0.',
                'material_id.required' => 'Please select a material.',
                'material_id.exists' => 'The selected material does not exist.',
                'phone.required' => 'A phone model is required.',
                'brand.required' => 'A brand name is required.',
                'colors.required' => 'At least one color must be selected.',
                'colors.*.quantity.required' => 'The quantity is required when a color is selected.',
                'colors.*.quantity.min' => 'The quantity of any selected color must be at least 1.',
                'images.*.image' => 'Each file must be an image.',
                'images.*.mimes' => 'Images must be in one of the following formats: jpeg, png, jpg, gif, svg.',
                'images.*.max' => 'Each image must not exceed 2 MB.',
                'delete_images.*.exists' => 'The selected image does not exist.',
            ]
        );

        $images = $product->getMedia('images')->count();
        if($images + count($validated['images'] ?? []) <= count($validated['delete_images'] ?? [])) {
            return redirect()->back()->withErrors(['delete_images' => 'You cannot delete all the images.']);
        }

        $product = $this->carryOut($validated);

        return redirect()->route('products.show', $product->slug);
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
