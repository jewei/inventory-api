<?php

namespace App\Http\Controllers;

use App\Events\UpdateInventoryQuantity;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Support\Arr;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InventoryResource::collection(Inventory::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function randomAction(UpdateInventoryRequest $request)
    {
        // Randomly choose 3 items.
        $threeInventories = Inventory::inRandomOrder()->limit(3)->get();

        // Randomly add / minus 1 unit of quantity.
        $threeInventories->each(function ($item) {
            $amount = Arr::random([1, -1]);

            // Async update quantity with queue.
            UpdateInventoryQuantity::dispatch($item, $amount);
        });

        return $this->index();
    }
}
