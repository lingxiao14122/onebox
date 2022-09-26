<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $formFields = $request->validated();

        if ($this->input('transaction_type') == 'audit') {
            foreach ($formFields['item_ids'] as $key => $item_id) {
                $quantity = $formFields['item_quantities'][$key];
                
                // validate stock count before commit
                $item = Item::find($item_id);
                $verifyStockCount = $item->stock_count + $quantity;
                if ($verifyStockCount <= 0) {
                    return back()->withErrors(['quantity_out_of_bound' => 'Warning! '.$item->name.' cannot go below zero']);
                }
            }
        }

        $transaction = Transaction::create([
            "user_id" => Auth::user()->id,
            "type" => $formFields['transaction_type'],
        ]);

        foreach ($formFields['item_ids'] as $key => $item_id) {
            $quantity = $formFields['item_quantities'][$key];
            $transaction->items()->attach($item_id, ['quantity' => $quantity]);
        }
        // TODO: consider using database transaction to revert when things go wrong or when error happen

        return redirect('/transaction');
    }

    public function show(Transaction $transaction)
    {
    }
}
