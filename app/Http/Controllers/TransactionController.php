<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
    }

    public function show(Transaction $transaction)
    {
    }
}
