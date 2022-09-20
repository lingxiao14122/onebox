<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    // $ace = Transaction::find(1);
    //     dd($ace->items);
    // $ace = new Transaction;
    // $ace->user_id = 1;
    // $ace->save();
    // $ace->items()->attach(1);
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

        // TODO: when stock out throw error when stock count at 0

        $transaction = Transaction::create([
            "user_id" => Auth::user()->id,
            "type" => $formFields['transaction_type'],
        ]);

        foreach ($formFields['item_ids'] as $key => $item_id) {
            $quantity = $formFields['item_quantities'][$key];
            $transaction->items()->attach($item_id, ['quantity' => $quantity]);
        }

        // TODO: calculate stock count on item table
        // TODO: consider using database transaction to revert when things go wrong or when error happen

        return redirect('/transaction');
    }

    public function show(Transaction $transaction)
    {
    }
}
