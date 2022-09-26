<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class ItemTransaction extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected static function booted()
    {
        // new record entry
        static::created(function ($itemTransaction) {
            // update stock count
            $item = Item::find($itemTransaction->item_id);
            $ori_stock_count = $item->stock_count;
            $new_stock_count = $ori_stock_count + $itemTransaction->quantity;
            $item->stock_count = $new_stock_count;
            $item->save();
            Log::info("Stock count $item->name ($item->name) updated from $ori_stock_count to $new_stock_count");
        });
    }
}
