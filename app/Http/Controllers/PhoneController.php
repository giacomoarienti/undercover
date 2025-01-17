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
        Gate::authorize('create', Phone::class);
        $validated = $request->validate([
            'name' => 'required|unique:phones|max:20',
            'brand' => 'required|max:20'
        ]);

        $brand = Brand::firstWhere(['name' => $validated['brand']]);
        if(!$brand) {
            Gate::authorize('create', Brand::class);
            $brand = Brand::create(['name' => $validated['brand']]);
        }
        $brand->addPhone($validated['name']);

        return PhoneController::index();
    }

    /**
     * Returns Json representation of all phone models
     */
    public function index()
    {
        return response()->json(Phone::all(), 200);
    }
}
