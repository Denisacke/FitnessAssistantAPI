<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeForm;
use App\Http\Services\RecipeService;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::with(['products', 'author'])
            ->filter()
            ->get();

        $mappedRecipes = $recipes->map(function ($recipe) {
            return [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'author' => $recipe->author->name ?? 'Unknown',
                'calories' => (int)$recipe->calories,
                'fat' => (float)$recipe->fat,
                'protein' => (float)$recipe->protein,
                'carbs' => (float)$recipe->carbs,
                'fibre' => (float)$recipe->fibre,
                'products' => $recipe->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'calories' => (int)$product->calories,
                        'fat' => (float)$product->fat,
                        'protein' => (float)$product->protein,
                        'carbs' => (float)$product->carbs,
                        'fibre' => (float)$product->fibre,
                        'quantity' => (int)$product->pivot->quantity,
                    ];
                })->values(),
            ];
        });

        return response()->json(['recipes' => $mappedRecipes]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RecipeForm $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $recipe = DB::transaction(function () use ($validated) {
                $products = [];
                $pivotData = [];

                foreach ($validated['products'] as $food) {
                    $pivotData[$food['product_id']] = [
                        'quantity' => $food['quantity']
                    ];

                    $products[] = [
                        'product' => Product::find($food['product_id']),
                        'quantity' => $food['quantity']
                    ];
                }

                $recipeMacros = RecipeService::computeRecipeCalories($products);

                $recipe = Recipe::create([
                    'name' => $validated['name'],
                    'user_id' => $validated['user_id'],
                    'created_by' => $validated['created_by'],
                    ...$recipeMacros
                ]);

                $recipe->products()->sync($pivotData);

                return $recipe;
            });

            return response()->json([
                'message' => 'Recipe created successfully!',
                'recipe' => $recipe->load('products')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while creating the recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function update(RecipeForm $request, string $id)
    {
        $validated = $request->validated();

        Log::info('got here with validated data');
        Log::info(json_encode($validated['products']));
        try {
            $recipe = DB::transaction(function () use ($validated, $id) {
                $products = [];
                $pivotData = [];

                foreach ($validated['products'] as $food) {
                    $pivotData[$food['product_id']] = [
                        'quantity' => $food['quantity']
                    ];

                    $products[] = [
                        'product' => Product::find($food['product_id']),
                        'quantity' => $food['quantity']
                    ];
                }

                $recipeMacros = RecipeService::computeRecipeCalories($products);
                $recipe = Recipe::find($id);

                $recipe->update([...$recipeMacros]);
                $recipe->products()->sync($pivotData);

                return $recipe;
            });

            return response()->json([
                'message' => 'Recipe updated successfully!',
                'recipe' => $recipe->load('products')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong while updating the recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): array
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        return ['success' => true];
    }
}
