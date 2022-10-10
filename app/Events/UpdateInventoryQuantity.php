<?php

namespace App\Events;

use App\Models\Inventory;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateInventoryQuantity
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Inventory
     */
    public $inventory;

    /**
     * @var int
     */
    public $amount;

    /**
     * Create a new event instance.
     */
    public function __construct(Inventory $inventory, int $amount)
    {
        $this->inventory = $inventory;
        $this->amount = $amount;
    }
}
