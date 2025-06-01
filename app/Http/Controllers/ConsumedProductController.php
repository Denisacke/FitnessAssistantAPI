<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumedProductForm;
use App\Models\ConsumedProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsumedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['products' => ConsumedProduct::filter()->with('product')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsumedProductForm $request)
    {
        $validated = $request->validated();
        ConsumedProduct::create([
            ...$validated,
            'consumed_at' => Carbon::now(),
        ]);

        return ['success' => true];
    }

    public function registerWaterIntake(Request $request)
    {
        $userId = $request->get('user_id');
        $quantity = $request->get('quantity');
        $waterProduct = Product::where('name', '=', Product::WATER_PRODUCT_NAME)->first();

        ConsumedProduct::create([
            'user_id' => $userId,
            'product_id' => $waterProduct->id,
            'quantity' => $quantity,
            'consumed_at' => Carbon::now(),
        ]);

        return ['success' => true];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
