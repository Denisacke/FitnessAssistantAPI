<?php

namespace App\Http\Services;

class RecipeService
{
    public static function computeRecipeCalories($products): array
    {
        $totalRecipeMacros = [
            'calories' => 0,
            'protein'  => 0,
            'fat'      => 0,
            'carbs'    => 0,
            'fibre'    => 0,
        ];

        $totalRecipeWeight = 0;

        foreach ($products as $product) {
            $quantity = $product['quantity'];

            $totalRecipeMacros['calories'] += ($product['product']->calories * $quantity) / 100;
            $totalRecipeMacros['protein']  += ($product['product']->protein * $quantity) / 100;
            $totalRecipeMacros['fat']      += ($product['product']->fat * $quantity) / 100;
            $totalRecipeMacros['carbs']    += ($product['product']->carbs * $quantity) / 100;
            $totalRecipeMacros['fibre']    += ($product['product']->fibre * $quantity) / 100;

            $totalRecipeWeight += $quantity;
        }

        if ($totalRecipeWeight == 0) {
            return [
                'calories' => 0,
                'protein'  => 0,
                'fat'      => 0,
                'carbs'    => 0,
                'fibre'    => 0,
            ];
        }

        return [
            'calories' => round(($totalRecipeMacros['calories'] / $totalRecipeWeight) * 100),
            'protein'  => round(($totalRecipeMacros['protein']  / $totalRecipeWeight) * 100),
            'fat'      => round(($totalRecipeMacros['fat']      / $totalRecipeWeight) * 100),
            'carbs'    => round(($totalRecipeMacros['carbs']    / $totalRecipeWeight) * 100),
            'fibre'    => round(($totalRecipeMacros['fibre']    / $totalRecipeWeight) * 100),
        ];
    }

}
