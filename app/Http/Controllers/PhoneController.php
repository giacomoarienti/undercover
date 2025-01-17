<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class PhoneController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:phones|max:20',
            'brand' => 'required|max:20'
        ]);
        Gate::authorize('create', Phone::class);

        $brand = Brand::firstWhere(['name' => $validated['brand']]);
        if(!$brand) {
            Gate::authorize('create', Brand::class);
            $brand = Brand::create(['name' => $validated['brand'], 'slug' => Str::slug($validated['brand'])]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Operazione completata con successo.',
            'phone' => $brand->addPhone($validated['name'])
        ], 200);
    }

    /**
     * Returns Json representation of all phone models
     */
    public function index()
    {
        return response()->json(Phone::all(), 200);
    }
}
