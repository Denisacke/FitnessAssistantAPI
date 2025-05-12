<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Models\ConsumedProduct;
use App\Models\ConsumedRecipe;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class ProductController extends Controller
{
    public function scrape(ProductService $productService): JsonResponse
    {
        $foodsToInsert = $productService->scrapeAllFoods();

        try {
            Product::insert($foodsToInsert);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

        return response()->json($foodsToInsert);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(['products' => Product::filter()->get()]);
    }

    public function getConsumedNutritionForDate(Request $request, $id)
    {
        $date = Carbon::parse($request->query('date'));

        $consumedProducts = ConsumedProduct::where('user_id', $id)
            ->whereDate('consumed_at', $date)
            ->with('product')
            ->get();

        $consumedRecipes = ConsumedRecipe::where('user_id', $id)
            ->whereDate('consumed_at', $date)
            ->with('recipe')
            ->get();

        $waterIntake = ConsumedProduct::where('user_id', $id)
            ->whereDate('consumed_at', $date)
            ->whereHas('product', function ($query) {
                $query->where('name', Product::WATER_PRODUCT_NAME);
            })
            ->get()
            ->sum('quantity');

        $totals = [
            'calories' => 0,
            'proteins' => 0,
            'fats' => 0,
            'carbs' => 0,
            'fibre' => 0,
            'water_intake' => $waterIntake,
        ];

        foreach ($consumedProducts as $cp) {
            if ($cp->product) {
                $multiplier = $cp->quantity / 100;
                $totals['calories'] += $multiplier * $cp->product->calories;
                $totals['proteins'] += $multiplier * $cp->product->protein;
                $totals['fats']     += $multiplier * $cp->product->fat;
                $totals['carbs']    += $multiplier * $cp->product->carbs;
                $totals['fibre']    += $multiplier * $cp->product->fibre;
            }
        }

        foreach ($consumedRecipes as $cr) {
            if ($cr->recipe) {
                $multiplier = $cr->quantity / 100;
                $totals['calories'] += $multiplier * $cr->recipe->calories;
                $totals['proteins'] += $multiplier * $cr->recipe->protein;
                $totals['fats']     += $multiplier * $cr->recipe->fat;
                $totals['carbs']    += $multiplier * $cr->recipe->carbs;
                $totals['fibre']    += $multiplier * $cr->recipe->fibre;
            }
        }

        foreach ($totals as $key => $value) {
            $totals[$key] = round($value);
        }

        return response()->json([
            'date' => $date->toDateString(),
            'user_id' => $id,
            'totals' => $totals,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
