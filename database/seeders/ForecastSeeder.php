<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daysleft = 100;
        while ($daysleft > 0) {
            foreach (Item::all() as $item) {
                // create in transaction
                $transaction = Transaction::create([
                    "user_id" => 1,
                    "type" => 'in',
                    "comment" => 'purchase from vendor',
                    "created_at" => Carbon::now()->subDays($daysleft),
                ]);
                // next date
                $daysleft--;

                $quantity = fake()->randomElement([11, 20, 30]);
                $transaction->items()->attach($item->id, ['quantity' => $quantity]);

                // transaction loop its items to do transaction out for each one
                // foreach ($transaction->items as $item) {
                $item_remaining_stock = $quantity;
                do {
                    $transaction_out_quantity = fake()->randomElement([-3, -4, -5]);
                    if (($item_remaining_stock + $transaction_out_quantity) < 0) break;
                    $transactionCustomerOrder = Transaction::create([
                        "user_id" => 1,
                        "type" => 'out',
                        "comment" => 'customer order',
                        "created_at" => Carbon::now()->subDays($daysleft),
                    ]);
                    $transactionCustomerOrder->items()->attach($item->id, ['quantity' => $transaction_out_quantity]);
                    $item_remaining_stock = $item_remaining_stock + $transaction_out_quantity;
                } while ($item_remaining_stock);
                $daysleft--;
                if (! ($daysleft > 0)) break;
            }
        }

        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $stock_count = $item->stock_count;
                $item->pivot->from_count = $stock_count;
                $item->pivot->to_count = $stock_count + $item->pivot->quantity;
                $item->pivot->save();
                $item->stock_count = $item->pivot->to_count;
                $item->save();
            }
        }
    }
}
