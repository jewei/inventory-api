<?php

namespace App\Listeners;

use App\Events\UpdateInventoryQuantity;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsyncUpdateInventoryQuantity implements ShouldQueue, ShouldBeUnique
{
    /**
     * Handle the event.
     */
    public function handle(UpdateInventoryQuantity $event)
    {
        $inventory = DB::table($event->inventory->getTable())
            ->where('id', $event->inventory->id)
            // Lock the selected rows in the table for updating.
            ->lockForUpdate()
            ->first()
        ;

        if (is_null($inventory)) {
            Log::error('Could not find inventory with id: '.$event->inventory->id);
        }

        $value = (int) $event->amount;

        if (0 === $value) {
            Log::warning('Inventory quantity unchanged.', ['id' => $inventory->id]);
        }

        if ($value < 0 && $inventory->quantity < 1) {
            Log::error('Inventory quantity could not be negative.', ['id' => $inventory->id]);
        }

        DB::table($event->inventory->getTable())
            ->where('id', $event->inventory->id)
            ->update([
                'quantity' => $finalValue = $inventory->quantity + $value,
            ])
        ;

        Log::debug(
            'Inventory quantity changed: '.$value,
            [
                'id' => $inventory->id,
                'updated_quantity' => $finalValue,
            ]
        );
    }
}
