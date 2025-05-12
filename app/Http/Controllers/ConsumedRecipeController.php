<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumedRecipeForm;
use App\Models\ConsumedRecipe;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsumedRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsumedRecipeForm $request)
    {
        $validated = $request->validated();
        ConsumedRecipe::create([
            ...$validated,
            'consumed_at' => Carbon::now(),
        ]);

        return ['success' => true];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
