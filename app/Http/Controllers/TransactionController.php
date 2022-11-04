<?php

namespace App\Http\Controllers;

use App\Events\TransactionFinished;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Integration;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\MinimumStockCount;
use App\Providers\SyncUpStockCountLazada;
use App\Services\IntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index()
    {
        $transaction = Transaction::all()->sortByDesc("id");
        return view('transaction.index', ["transactions" => $transaction]);
    }

    public function create()
    {
        return view('transaction.create');
    }

    public function in()
    {
        return view('transaction.in');
    }

    public function out()
    {
        return view('transaction.out');
    }

    public function audit()
    {
        return view('transaction.audit');
    }

    public function store(StoreTransactionRequest $request)
    {
        $products = array_combine($request->item_ids, $request->item_quantities);

        // when mode stock out flip quantity to negative
        if ($request->transaction_type == "out")
            foreach ($products as $id => $quantity) {
                    $products[$id] = -$quantity;
            }
        if ($request->transaction_type == "audit")
            foreach ($products as $id => $quantity) {
                if ($quantity < 0) {
                    $products[$id] = -$quantity;
                }
            }

        // check ahead if stock count will be negative number
        // use middleware? ask wei liang
        foreach ($products as $id => $quantity) {
            $item = Item::find($id);
            $verifyStockCount = $item->stock_count + $quantity;
            if ($verifyStockCount < 0) {
                return back()->withErrors(['quantity_out_of_bound' => "($item->name) Warning! cannot decrease stock below zero"]);
            }
        }

        // storing
        $transaction = Transaction::create([
            "user_id" => Auth::user()->id,
            "type" => $request->transaction_type,
            "comment" => $request->comment,
        ]);

        foreach ($products as $id => $quantity) {
            $transaction->items()->attach($id, ['quantity' => $quantity]);
        }

        // after store transaction
        foreach ($transaction->items as $item) {
            $original = $item->stock_count;
            $mutate = $item->pivot->quantity;
            $new = $original + $mutate;

            $item->stock_count = $new;
            $item->save();

            $item->pivot->from_count = $original;
            $item->pivot->to_count = $new;
            $item->pivot->save();

            Log::info("Stock count ($item->name) update from $original to $new");

            $belowMinimum = $item->stock_count < $item->minimum_stock;
            if ($belowMinimum) {
                Log::info("Low stock identified (" . $item->name . ") min:$item->minimum_stock quantity left:$item->stock_count, sending notification");
                $notification = (new MinimumStockCount($item, $item->stock_count));
                $request->user()->notify($notification);
            }
        }

        $integrationService = new IntegrationService;
        $integrationService->syncUp();

        return redirect('/transaction')->with('message', 'Transaction recorded sucessfully');
    }
}
