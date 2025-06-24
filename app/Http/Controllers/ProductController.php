<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Models\ConsumedProduct;
use App\Models\ConsumedRecipe;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function getConsumedFoodsPerUser($id): JsonResponse
    {
        $consumedProducts = ConsumedProduct::where('user_id', $id)
            ->with('product')
            ->whereDoesntHave('product', function ($query) {
                $query->where('name', Product::WATER_PRODUCT_NAME);
            })
            ->orderBy('consumed_at', 'DESC')
            ->get();

        $consumedRecipes = ConsumedRecipe::where('user_id', $id)
            ->with('recipe')
            ->orderBy('consumed_at', 'DESC')
            ->get();

        foreach ($consumedProducts as $cp) {
            if (is_null($cp->product_name)) {
                $multiplier = $cp->quantity / 100;
                $cp['calories'] = round($multiplier * $cp->product->calories);
                $cp['protein'] = round($multiplier * $cp->product->protein);
                $cp['fats'] = round($multiplier * $cp->product->fat);
                $cp['carbs'] = round($multiplier * $cp->product->carbs);
                $cp['fibre'] = round($multiplier * $cp->product->fibre);
                $cp['name'] = $cp->product->name;

                $cp->update([
                    'calories' => round($multiplier * $cp->product->calories),
                    'protein' => round($multiplier * $cp->product->protein),
                    'fats' => round($multiplier * $cp->product->fat),
                    'carbs' => round($multiplier * $cp->product->carbs),
                    'fibre' => round($multiplier * $cp->product->fibre),
                    'name' => $cp->product->name
                ]);
            }
        }

        foreach ($consumedRecipes as $cr) {
            if (is_null($cr->recipe_name)) {
                $multiplier = $cr->quantity / 100;
                $cr['calories'] = round($multiplier * $cr->recipe->calories);
                $cr['protein'] = round($multiplier * $cr->recipe->protein);
                $cr['fats'] = round($multiplier * $cr->recipe->fat);
                $cr['carbs'] = round($multiplier * $cr->recipe->carbs);
                $cr['fibre'] = round($multiplier * $cr->recipe->fibre);
                $cr['name'] = $cr->recipe->name;

                $cr->update([
                    'calories' => round($multiplier * $cr->recipe->calories),
                    'protein' => round($multiplier * $cr->recipe->protein),
                    'fats' => round($multiplier * $cr->recipe->fat),
                    'carbs' => round($multiplier * $cr->recipe->carbs),
                    'fibre' => round($multiplier * $cr->recipe->fibre),
                    'name' => $cr->recipe->name,
                ]);
            }
        }

        return response()->json(['foods' => [...$consumedProducts, ...$consumedRecipes]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function getConsumedNutritionPerDay(Request $request, $id)
    {
        $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->query('end_date'))->endOfDay();

        $results = [];

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $nextDate = $currentDate->copy()->endOfDay();

            $consumedProducts = ConsumedProduct::where('user_id', $id)
                ->whereBetween('consumed_at', [$currentDate, $nextDate])
                ->with('product')
                ->get();

            $consumedRecipes = ConsumedRecipe::where('user_id', $id)
                ->whereBetween('consumed_at', [$currentDate, $nextDate])
                ->with('recipe')
                ->get();

            $waterIntake = ConsumedProduct::where('user_id', $id)
                ->whereBetween('consumed_at', [$currentDate, $nextDate])
                ->whereHas('product', function ($query) {
                    $query->where('name', Product::WATER_PRODUCT_NAME);
                })
                ->sum('quantity');

            $totals = [
                'date' => $currentDate->toDateString(),
                'calories' => 0,
                'protein' => 0,
                'fats' => 0,
                'carbs' => 0,
                'fibre' => 0,
                'water_intake' => $waterIntake,
            ];

            foreach ($consumedProducts as $cp) {
                if ($cp->product) {
                    $multiplier = $cp->quantity / 100;
                    $totals['calories'] += $multiplier * $cp->product->calories;
                    $totals['protein'] += $multiplier * $cp->product->protein;
                    $totals['fats'] += $multiplier * $cp->product->fat;
                    $totals['carbs'] += $multiplier * $cp->product->carbs;
                    $totals['fibre'] += $multiplier * $cp->product->fibre;

                    $cp->update([
                        'calories' => round($multiplier * $cp->product->calories),
                        'protein' => round($multiplier * $cp->product->protein),
                        'fats' => round($multiplier * $cp->product->fat),
                        'carbs' => round($multiplier * $cp->product->carbs),
                        'fibre' => round($multiplier * $cp->product->fibre),
                        'name' => $cp->product->name
                    ]);
                } else {
                    $multiplier = $cp->quantity / 100;
                    $totals['calories'] += $multiplier * $cp->calories;
                    $totals['protein'] += $multiplier * $cp->protein;
                    $totals['fats'] += $multiplier * $cp->fat;
                    $totals['carbs'] += $multiplier * $cp->carbs;
                    $totals['fibre'] += $multiplier * $cp->fibre;
                }
            }

            foreach ($consumedRecipes as $cr) {
                if ($cr->recipe) {
                    $multiplier = $cr->quantity / 100;
                    $totals['calories'] += $multiplier * $cr->recipe->calories;
                    $totals['protein'] += $multiplier * $cr->recipe->protein;
                    $totals['fats'] += $multiplier * $cr->recipe->fat;
                    $totals['carbs'] += $multiplier * $cr->recipe->carbs;
                    $totals['fibre'] += $multiplier * $cr->recipe->fibre;

                    $cr->update([
                        'calories' => round($multiplier * $cr->recipe->calories),
                        'protein' => round($multiplier * $cr->recipe->protein),
                        'fats' => round($multiplier * $cr->recipe->fat),
                        'carbs' => round($multiplier * $cr->recipe->carbs),
                        'fibre' => round($multiplier * $cr->recipe->fibre),
                        'name' => $cr->recipe->name,
                    ]);
                } else {
                    $multiplier = $cr->quantity / 100;
                    $totals['calories'] += $multiplier * $cr->calories;
                    $totals['protein'] += $multiplier * $cr->protein;
                    $totals['fats'] += $multiplier * $cr->fat;
                    $totals['carbs'] += $multiplier * $cr->carbs;
                    $totals['fibre'] += $multiplier * $cr->fibre;
                }
            }

            foreach ($totals as $key => $value) {
                if ($key !== 'date') {
                    $totals[$key] = round($value);
                }
            }

            $results[] = $totals;

            $currentDate->addDay()->startOfDay();
        }

        return response()->json($results);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
