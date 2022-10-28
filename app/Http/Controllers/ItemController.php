<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Services\IntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('item.index', ["items" => Item::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => "required|unique:App\Models\Item,name",
            "sku" => "required|unique:App\Models\Item,sku",
            "description" => "between:0,300",
            "purchase_price" => "nullable|numeric",
            "selling_price" => "nullable|numeric",
            "minimum_stock" => "nullable|numeric",
            "stock_count" => "nullable|numeric|min:0",
            "lead_time" => "nullable|numeric|min:0",
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('itemImages', 'public');
        }

        // if stock count is null then 0
        $formFields['stock_count'] = $formFields['stock_count'] ?? 0;
        $formFields['minimum_stock'] = $formFields['minimum_stock'] ?? 0;
        $formFields['lead_time'] = $formFields['lead_time'] ?? 0;

        $item = Item::create($formFields);

        if ($formFields['stock_count'] == 0) return redirect(route('item.index'));

        $transaction = Transaction::create([
            "user_id" => Auth::id(),
            "type" => "audit",
            "comment" => "Product created",
        ]);
        
        $transaction->items()->attach($item->id, [
            'quantity' => $formFields['stock_count'],
            'from_count' => 0,
            'to_count' => $formFields['stock_count']
        ]);

        $integrationService = new IntegrationService;
        $integrationService->syncUp();

        return redirect(route('item.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $formFields = $request->validate([
            "name" => "required",
            "sku" => "required",
            "description" => "between:0,300",
            "purchase_price" => "nullable|numeric",
            "selling_price" => "nullable|numeric",
            "minimum_stock" => "nullable|numeric",
            "lead_time" => "nullable|numeric|min:0",
        ]);

        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('itemImages', 'public');
        }

        $LOGICAL_MIN_STOCK = 0;
        $formFields['minimum_stock'] = $formFields['minimum_stock'] ? $formFields['minimum_stock'] : $LOGICAL_MIN_STOCK;

        $item->update($formFields);

        $integrationService = new IntegrationService;
        $integrationService->syncUp();

        return redirect(route('item.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect(route('item.index'));
    }
}
