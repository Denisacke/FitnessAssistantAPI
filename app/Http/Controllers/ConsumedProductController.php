<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsumedProductForm;
use App\Models\ConsumedProduct;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsumedProductForm $request)
    {
        $validated = $request->validated();
        $consumption = ConsumedProduct::create([
            ...$validated,
            'consumed_at' => Carbon::now(),
        ]);

        Log::info(json_encode($consumption));

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
