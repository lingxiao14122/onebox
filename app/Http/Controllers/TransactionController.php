<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\MinimumStockCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transaction.index', ["transactions" => Transaction::all()]);
    }

    public function create()
    {
        return view('transaction.create');
    }

    public function store(StoreTransactionRequest $request)
    {
        // validating
        $formFields = $request->validated();

        // TODO: format validated item data to a pair and not seperate array

        // when mode stock out flip quantity to negative
        if ($formFields['transaction_type'] == "out")
            foreach ($formFields['item_quantities'] as $key => $quantity) {
                $formFields['item_quantities'][$key] = -$quantity;
            }

        // check if stock count will be negative number
        foreach ($formFields['item_ids'] as $key => $id) {
            $quantity = $formFields['item_quantities'][$key];
            $item = Item::find($id);
            $verifyStockCount = $item->stock_count + $quantity;
            if ($verifyStockCount < 0) {
                return back()->withErrors(['quantity_out_of_bound' => "($item->name) Warning! cannot decrease stock below zero"]);
            }
        }

        // storing
        $transaction = Transaction::create([
            "user_id" => Auth::user()->id,
            "type" => $formFields['transaction_type'],
        ]);

        foreach ($formFields['item_ids'] as $key => $item_id) {
            $quantity = $formFields['item_quantities'][$key];
            $transaction->items()->attach($item_id, ['quantity' => $quantity]);
        }

        // after store transaction
        foreach ($transaction->items as $item) {
            $ori_stock_count = $item->stock_count;
            $new_stock_count = $ori_stock_count + $item->pivot->quantity;
            $item->stock_count = $new_stock_count;
            Log::info("Stock count ($item->name) updating from $ori_stock_count to $new_stock_count");
            $item->save();

            $belowMinimum = $item->stock_count < $item->minimum_stock;
            if ($belowMinimum) {
                Log::info("Low stock detected (" . $item->name . ") min:$item->minimum_stock actual:$item->stock_count, sending notification to user");
                $request->user()->notify(new MinimumStockCount($item));
            }
        }

        return redirect('/transaction')->with('message', 'Transaction recorded sucessfully');
    }

    public function show(Transaction $transaction)
    {
    }
}
